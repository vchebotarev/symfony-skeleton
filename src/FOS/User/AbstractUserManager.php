<?php

namespace App\FOS\User;

use FOS\UserBundle\Doctrine\UserManager;
use FOS\UserBundle\Model\UserInterface;

/**
 * Сюда поместил то чем пользоваться не будет в рамках FOS
 */
class AbstractUserManager extends UserManager
{
    /**
     * @deprecated слишком жирный запрос
     */
    public function findUsers()
    {
        return [];
    }

    /**
     * @deprecated регистрации через модель не будет
     */
    public function updateCanonicalFields(UserInterface $user)
    {
        return;
    }

    /**
     * @deprecated регистрации через модель не будет
     */
    public function updatePassword(UserInterface $user)
    {
        return;
    }
}
