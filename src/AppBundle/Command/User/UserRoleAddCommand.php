<?php

namespace AppBundle\Command\User;

use App\Symfony\Command\AbstractContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class UserRoleAddCommand extends AbstractContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:user:role:add');
        $this->setAliases(['fos:user:promote',]);
        $this->setDescription('Add role to user');
        $this->addArgument('user', InputArgument::REQUIRED, 'User id or username or email');
        $this->addArgument('role', InputArgument::REQUIRED, 'Role');
    }

    /**
     * @inheritdoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $userData = $input->getArgument('user');
        $role     = $input->getArgument('role');

        //todo validate role

        $user = $this->getContainer()->get('app.user.manager')->findUserByIdOrUsernameOrEmail($userData);
        if (!$user) {
            $output->writeln('<error>User not found</error>');
            return;
        }

        $this->getContainer()->get('app.user.manipulator')->roleAdd($user, $role);

        $output->writeln('<info>Role was successfully added to user</info>');
    }

    /**
     * @inheritdoc
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
        if (!$input->getArgument('role')) {
            $question = new Question('Please choose a role:');
            $question->setValidator(function ($role) {
                if (empty($role)) {
                    throw new \Exception('Role can not be empty');
                }

                return $role;
            });
            $questions['role'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }

}
