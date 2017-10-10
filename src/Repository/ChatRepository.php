<?php

namespace App\Repository;

use App\Doctrine\ORM\EntityRepository;
use App\Entity\Chat;
use App\Entity\User;

class ChatRepository extends EntityRepository
{
    /**
     * Ищет чат в котором именно этот набор пользователей ни больше, ни меньше
     * @param User[] $users
     * @return Chat|null
     */
    public function findPrivateChatByUsers($users)
    {
        $expr = $this->getEntityManager()->getExpressionBuilder();

        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.users', 'cu')
            ->addSelect('COUNT(cu.id) AS HIDDEN cc')
            ->andWhere($expr->in('cu.user', array_map(function (User $user){
                return $user->getId();
            }, $users)))
            ->addGroupBy('c.id')
            ->andHaving('cc = '. count($users));

        return $qb->getQuery()->getOneOrNullResult();
    }

}
