<?php

namespace App\Ticket\Message\Search;

use App\Entity\TicketMessage;
use App\User\UserManager;
use Doctrine\ORM\EntityManager;

class TicketMessageSearchItemTransformer
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @param EntityManager $em
     * @param UserManager   $userManager
     */
    public function __construct(EntityManager $em, UserManager $userManager)
    {
        $this->em          = $em;
        $this->userManager = $userManager;
    }

    /**
     * @param TicketMessage[] $ticketMessages
     * @return TicketMessageSearchItem[]
     */
    public function transform($ticketMessages)
    {
        //todo
    }

}
