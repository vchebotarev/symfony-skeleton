<?php

namespace App\Doctrine\Repository;

use App\Doctrine\ORM\EntityRepository;
use App\Doctrine\Entity\User;
use Doctrine\Common\Collections\Criteria;

class UserReviewRepository extends EntityRepository
{
    /**
     * @param User $user
     * @return array
     */
    public function findByUser(User $user)
    {
        $expr = $this->em->getExpressionBuilder();

        $reviews = $this->createQueryBuilder('ur')
            ->leftJoin('ur.userCreated', 'uc')
            ->andWhere($expr->eq('ur.user', $user->getId()))
            ->addOrderBy('ur.dateCreated', Criteria::DESC)
            ->getQuery()
            ->getResult();

        return $reviews;
    }

    /**
     * @param User $userTo
     * @param User $userFrom
     * @return bool
     */
    public function hasReviewFromUser(User $userTo, User $userFrom) : bool
    {
        $count = $this->countBy([
            'user'        => $userTo,
            'userCreated' => $userFrom,
        ]);
        return $count > 0;
    }
}
