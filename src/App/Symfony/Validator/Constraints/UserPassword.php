<?php

namespace App\Symfony\Validator\Constraints;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword as BaseUserPassword;

class UserPassword extends BaseUserPassword
{
    /**
     * @var bool
     */
    public $notValidMode = false;

    /**
     * @var string
     */
    public $service = 'validator.user_password';

    /**
     * @var UserInterface|null
     */
    public $user;

}
