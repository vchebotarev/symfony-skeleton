<?php

namespace App\Doctrine\Repository;

use App\Doctrine\ORM\EntityRepository;
use App\Doctrine\Entity\User;
use App\Doctrine\Entity\UserAuthLog;
use Doctrine\Common\Collections\Criteria;

class UserAuthLogRepository extends EntityRepository
{
    /**
     * @param User $user
     * @return UserAuthLog[]
     */
    public function findByUser(User $user)
    {
        return $this->findBy([
            'user' => $user,
        ], [
            'dateCreated' => Criteria::DESC,
        ]);
    }

}
