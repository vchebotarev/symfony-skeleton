<?php

namespace App\Auth;

use Symfony\Component\Security\Core\User\UserChecker as BaseUserChecker;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker extends BaseUserChecker
{
    public function checkPreAuth(UserInterface $user)
    {
        return;
    }

    public function checkPostAuth(UserInterface $user)
    {
        //Необходимо, чтобы выводило пользователю о "проблемах" с аккаунтом, только после того как он ввел правильный пароль, а не каждому встречному
        parent::checkPreAuth($user);
        parent::checkPostAuth($user);
    }
}
