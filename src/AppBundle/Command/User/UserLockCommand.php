<?php

namespace AppBundle\Command\User;

use App\Symfony\Command\AbstractContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class UserLockCommand extends AbstractContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:user:lock');
        $this->setDescription('Lock user');
        $this->addArgument('user', InputArgument::REQUIRED, 'User id or username or email');
    }

    /**
     * @inheritdoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $userData = $input->getArgument('user');

        $user = $this->getContainer()->get('app.user.manager')->findUserByIdOrUsernameOrEmail($userData);
        if (!$user) {
            $output->writeln('<error>User not found</error>');
            return;
        }

        $this->getContainer()->get('app.user.manipulator')->lock($user);

        $output->writeln('<info>User was successfully locked</info>');
    }

    /**
     * @inheritdoc
     */
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
