<?php

namespace App\Symfony\Validator\Constraints;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UserPasswordValidator extends  ConstraintValidator
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * @param TokenStorageInterface   $tokenStorage
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(TokenStorageInterface $tokenStorage, EncoderFactoryInterface $encoderFactory)
    {
        $this->tokenStorage   = $tokenStorage;
        $this->encoderFactory = $encoderFactory;
    }

    public function validate($password, Constraint $constraint)
    {
        if (!$constraint instanceof UserPassword) {
            throw new UnexpectedTypeException($constraint, UserPassword::class);
        }

        if (null === $password || '' === $password) {
            return;
        }

        if ($constraint->user) {
            $user = $constraint->user;
        } else {
            $user = $this->tokenStorage->getToken()->getUser();
        }

        if (!$user instanceof UserInterface) {
            throw new ConstraintDefinitionException('The User object must implement the UserInterface interface.');
        }

        $passwordValid = $this->encoderFactory->getEncoder($user)->isPasswordValid($user->getPassword(), $password, $user->getSalt());

        if ($constraint->notValidMode && !$passwordValid) {
            return;
        }

        if (!$constraint->notValidMode && $passwordValid) {
            return;
        }

        $this->context->addViolation($constraint->message);
    }
}
