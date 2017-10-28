<?php

namespace App\Repository;

use App\Doctrine\ORM\EntityRepository;
use App\Entity\User;
use App\Entity\UserAuthLog;
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
