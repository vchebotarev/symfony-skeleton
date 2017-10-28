<?php

namespace App\Command\User;

use App\Symfony\Command\AbstractContainerAwareCommand;
use App\Symfony\Validator\Constraints\Chain;
use App\Symfony\Validator\Constraints\Password;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Constraints\NotNull;

class UserPasswordChangeCommand extends AbstractContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:user:password:change');
        $this->setAliases(['fos:user:change-password',]);
        $this->setDescription('Change user password');
        $this->addArgument('user', InputArgument::REQUIRED, 'User id or username or email');
        $this->addArgument('password', InputArgument::REQUIRED, 'New password');
    }

    /**
     * @inheritdoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $userData = $input->getArgument('user');
        $password = $input->getArgument('password');

        $user = $this->getContainer()->get('app.user.manager')->findUserByIdOrUsernameOrEmail($userData);
        if (!$user) {
            $output->writeln('<error>User not found</error>');
            return;
        }

        $errors = $this->getContainer()->get('validator')->validate($password, [
            new Chain([
                new NotNull(),
                new Password(),
            ]),
        ]);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $output->writeln('<error>'.$error->getMessage().'</error>');
            }
            return;
        }

        $this->getContainer()->get('app.user.manipulator')->changePassword($user, $password);

        $output->writeln('<info>Password was successfully changed</info>');
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
        if (!$input->getArgument('password')) {
            $question = new Question('Please choose a password:');
            $question->setValidator(function ($password) {
                if (empty($password)) {
                    throw new \Exception('Password can not be empty');
                }

                return $password;
            });
            $questions['password'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }

}
