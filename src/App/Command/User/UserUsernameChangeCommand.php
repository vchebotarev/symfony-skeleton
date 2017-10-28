<?php

namespace App\Command\User;

use App\Symfony\Command\AbstractContainerAwareCommand;
use App\Symfony\Validator\Constraints\Chain;
use App\Symfony\Validator\Constraints\EntityExists;
use App\Symfony\Validator\Constraints\Username;
use App\Entity\User;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Constraints\NotNull;

class UserUsernameChangeCommand extends AbstractContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:user:username:change');
        $this->setDescription('Change user username');
        $this->addArgument('user', InputArgument::REQUIRED, 'User id or username or email');
        $this->addArgument('username', InputArgument::REQUIRED, 'User username');
    }

    /**
     * @inheritdoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $userData = $input->getArgument('user');
        $username = $input->getArgument('username');

        $user = $this->getContainer()->get('app.user.manager')->findUserByIdOrUsernameOrEmail($userData);
        if (!$user) {
            $output->writeln('<error>User not found</error>');
            return;
        }

        $errors = $this->getContainer()->get('validator')->validate($username, [
            new Chain([
                new NotNull(),
                new Username(),
                new EntityExists([
                    'notExistMode' => true,
                    'entityClass'  => User::class,
                    'field'        => 'username',
                    'message'      => 'Пользователь с таким username уже есть в системе',
                ]),
            ]),
        ]);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $output->writeln('<error>'.$error->getMessage().'</error>');
            }
            return;
        }

        $this->getContainer()->get('app.user.manipulator')->changeUsername($user, $username);

        $output->writeln('<info>Username was successfully changed</info>');
    }

    /**
     * @inheritdoc
     */
    public function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = [];

        if (!$input->getArgument('user')) {
            $question = new Question('Please enter user id or username or email:');
            $question->setValidator(function ($userData) {
                if (empty($userData)) {
                    throw new \Exception('User data can not be empty');
                }
                return $userData;
            });
            $questions['user'] = $question;
        }
        if (!$input->getArgument('username')) {
            $question = new Question('Please choose a username:');
            $question->setValidator(function ($username) {
                if (empty($username)) {
                    throw new \Exception('username can not be empty');
                }

                return $username;
            });
            $questions['username'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }

}
