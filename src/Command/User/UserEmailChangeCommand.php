<?php

namespace App\Command\User;

use App\Symfony\Command\AbstractContainerAwareCommand;
use App\Symfony\Validator\Constraints\Chain;
use App\Symfony\Validator\Constraints\EntityExists;
use App\Entity\User;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;

class UserEmailChangeCommand extends AbstractContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:user:email:change');
        $this->setDescription('Change user email');
        $this->addArgument('user', InputArgument::REQUIRED, 'User id or username or email');
        $this->addArgument('email', InputArgument::REQUIRED, 'New email');
    }

    /**
     * @inheritDoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $userData = $input->getArgument('user');
        $email    = $input->getArgument('email');

        $user = $this->getContainer()->get('app.user.manager')->findUserByIdOrUsernameOrEmail($userData);
        if (!$user) {
            $output->writeln('<error>User not found</error>');
            return;
        }

        $errors = $this->getContainer()->get('validator')->validate($email, [
            new Chain([
                new NotNull(),
                new Email(),
                new EntityExists([
                    'notExistMode' => true,
                    'entityClass'  => User::class,
                    'field'        => 'email',
                    'message'      => 'Пользователь с таким e-mail уже есть в системе',
                ]),
            ]),
        ]);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $output->writeln('<error>'.$error->getMessage().'</error>');
            }
            return;
        }

        $this->getContainer()->get('app.user.manipulator')->changeEmail($user, $email);

        $output->writeln('<info>Email was successfully changed</info>');
    }

    /**
     * @inheritDoc
     */
    public function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = [];

        if (!$input->getArgument('user')) {
            $question = new Question('Please enter user id or username or email:');
            $question->setValidator(function ($username) {
                if (empty($username)) {
                    throw new \Exception('User data can not be empty');
                }
                return $username;
            });
            $questions['user'] = $question;
        }
        if (!$input->getArgument('email')) {
            $question = new Question('Please choose an email:');
            $question->setValidator(function ($email) {
                if (empty($email)) {
                    throw new \Exception('Email can not be empty');
                }

                return $email;
            });
            $questions['email'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }

}
