<?php

namespace App\Auth\UserProvider;

use FOS\UserBundle\Security\UserProvider as FOSUserProvider;

class UserProviderMain extends FOSUserProvider
{
    /**
     * @inheritDoc
     */
    protected function findUser($username)
    {
        return $this->userManager->findUserByUsernameOrEmail($username);
    }

}
