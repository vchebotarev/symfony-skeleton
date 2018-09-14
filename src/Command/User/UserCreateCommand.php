<?php

namespace App\Command\User;

use App\Symfony\Command\AbstractContainerAwareCommand;
use App\Symfony\Validator\Constraints\EntityExists;
use App\Symfony\Validator\Constraints\Password;
use App\Symfony\Validator\Constraints\Username;
use App\Entity\User;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;

class UserCreateCommand extends AbstractContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:user:create');
        $this->setAliases(['fos:user:create',]);
        $this->setDescription('Create new user');
        $this->addArgument('username', InputArgument::REQUIRED, 'Username');
        $this->addArgument('email', InputArgument::REQUIRED, 'Email');
        $this->addArgument('password', InputArgument::REQUIRED, 'Password');
    }

    /**
     * @inheritDoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $email    = $input->getArgument('email');
        $password = $input->getArgument('password');

        $errors = $this->getContainer()->get('validator')->validate([
            'username' => $username,
            'email'    => $email,
            'password' => $password,
        ], [
            new Collection([
                'fields' => [
                    'username' => [
                        new NotNull(),
                        new Username(),
                        new EntityExists([
                            'notExistMode' => true,
                            'entityClass'  => User::class,
                            'field'        => 'username',
                            'message'      => 'Пользователь с таким username уже есть в системе',
                        ]),
                    ],
                    'email' => [
                        new NotNull(),
                        new Email(),
                        new EntityExists([
                            'notExistMode' => true,
                            'entityClass'  => User::class,
                            'field'        => 'email',
                            'message'      => 'Пользователь с таким e-mail уже есть в системе',
                        ]),
                    ],
                    'password' => [
                        new NotNull(),
                        new Password(),
                    ],
                ]
            ])
        ]);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $output->writeln('<error>'.$error->getMessage().'</error>');
            }
            return;
        }

        $user = $this->getContainer()->get('app.user.manipulator')->create($username, $email, $password);

        $output->writeln('<info>User was successfully created with id: '. $user->getId().'</info>');
    }

    /**
     * @inheritDoc
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = array();

        if (!$input->getArgument('username')) {
            $question = new Question('Please choose a username:');
            $question->setValidator(function ($username) {
                if (empty($username)) {
                    throw new \Exception('Username can not be empty');
                }

                return $username;
            });
            $questions['username'] = $question;
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

        if (!$input->getArgument('password')) {
            $question = new Question('Please choose a password:');
            $question->setValidator(function ($password) {
                if (empty($password)) {
                    throw new \Exception('Password can not be empty');
                }

                return $password;
            });
            $question->setHidden(true);
            $questions['password'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }

}
