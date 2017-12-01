<?php

namespace App\Ticket;

use App\Entity\User;
use Doctrine\ORM\EntityManager;

class TicketCounter
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
     * Кол-во непрочитанных тикетов по типу
     * @param User $user
     * @return int
     */
    public function countUnread(User $user) : int
    {
        //todo

        return 0;
    }

    /**
     * Кол-во непрочитанных сообщений в тикетах по типу
     * @param User $user
     * @return int
     */
    public function countUnreadMessages(User $user) : int
    {
        //todo

        return 0;
    }

}
