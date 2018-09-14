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
     * @inheritDoc
     */
    public function findUsers()
    {
        return array();
    }

    /**
     * @deprecated регистрации через модель не будет
     * @inheritDoc
     */
    public function updateCanonicalFields(UserInterface $user)
    {
        return;
    }

    /**
     * @deprecated регистрации через модель не будет
     * @inheritDoc
     */
    public function updatePassword(UserInterface $user)
    {
        return;
    }

}
