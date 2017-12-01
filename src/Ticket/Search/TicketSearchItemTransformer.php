<?php

namespace App\Ticket\Search;

use App\Entity\Ticket;
use App\User\UserManager;
use Doctrine\ORM\EntityManager;

class TicketSearchItemTransformer
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
     * @param Ticket[] $tickets
     * @return TicketSearchItem[]
     */
    public function transform($tickets)
    {
        //todo
    }

}
