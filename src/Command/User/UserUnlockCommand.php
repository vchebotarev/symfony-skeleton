<?php

namespace App\Command\User;

use App\Symfony\Command\AbstractContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class UserUnlockCommand extends AbstractContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:user:unlock');
        $this->setDescription('Unlock user');
        $this->addArgument('user', InputArgument::REQUIRED, 'User id or username or email');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $userData = $input->getArgument('user');

        $user = $this->getContainer()->get('app.user.manager')->findUserByIdOrUsernameOrEmail($userData);
        if (!$user) {
            $output->writeln('<error>User not found</error>');
            return;
        }

        $this->getContainer()->get('app.user.manipulator')->unlock($user);

        $output->writeln('<info>User was successfully unlocked</info>');
    }

    public function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('user')) {
            $question = new Question('Please enter user id or username or email:');
            $question->setValidator(function ($userData) {
                if (empty($userData)) {
                    throw new \Exception('User data can not be empty');
                }
                return $userData;
            });
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument('user', $answer);
        }
    }

}
