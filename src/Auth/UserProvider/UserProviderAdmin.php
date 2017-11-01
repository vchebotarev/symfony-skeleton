<?php

namespace App\Auth\UserProvider;

use App\Entity\User;

class UserProviderAdmin extends UserProviderMain
{
    /**
     * {@inheritDoc}
     */
    protected function findUser($username)
    {
        $user = $this->userManager->findUserByUsernameOrEmail($username);;
        if ($user && $user->hasRole(User::ROLE_ADMIN)) {
            return $user;
        }
        return null;
    }

}