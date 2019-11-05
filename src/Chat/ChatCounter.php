<?php

namespace App\Chat;

use App\Doctrine\Entity\Chat;
use App\Doctrine\Entity\ChatMessageUser;
use App\Doctrine\Entity\User;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;

class ChatCounter
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param User $user
     * @return int
     */
    public function countUnreadChats(User $user) : int
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('COUNT(DISTINCT c.id)')
            ->from(Chat::class, 'c')
            ->leftJoin('c.messages', 'cm')
            ->leftJoin('cm.users', 'cmu')
            ->andWhere('cmu.user = :user')
            ->andWhere('cm.user != :user')
            ->setParameter('user', $user)
            ->andWhere('cmu.isRead = :is_read')
            ->setParameter('is_read', false)
            ->addGroupBy('cmu.user')
        ;

        return (int)$qb->getQuery()->getOneOrNullResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
    }

    /**
     * @param User $user
     * @return int
     */
    public function countUnreadMessages(User $user) : int
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('COUNT(cmu.id)')
            ->from(ChatMessageUser::class, 'cmu')
            ->leftJoin('cmu.message', 'cm')
            ->andWhere('cmu.user = :user')
            ->andWhere('cm.user != :user')
            ->setParameter('user', $user)
            ->andWhere('cmu.isRead = :is_read')
            ->setParameter('is_read', false)
            ->addGroupBy('cmu.user')
        ;

        return (int)$qb->getQuery()->getOneOrNullResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
    }
}
