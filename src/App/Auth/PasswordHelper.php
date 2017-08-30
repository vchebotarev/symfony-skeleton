<?php

namespace App\Auth;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class PasswordHelper
{
    /**
     * @var EncoderFactoryInterface
     */
    protected $passwordEncoderFactory;

    /**
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->passwordEncoderFactory = $encoderFactory;
    }

    /**
     * @param string $password
     * @param User   $user
     * @return string
     */
    public function encodePassword($password, User $user)
    {
        return $this->passwordEncoderFactory->getEncoder($user)->encodePassword($password, '');
    }

    /**
     * @param string $encodedPassword
     * @param string $rawPassword
     * @param User   $user
     * @return bool
     */
    public function isPasswordValid($encodedPassword, $rawPassword, User $user)
    {
        return $this->passwordEncoderFactory->getEncoder($user)->isPasswordValid($encodedPassword, $rawPassword, '');
    }

}
