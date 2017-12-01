<?php

namespace App\Ticket;

use App\Entity\Ticket;
use App\Entity\User;
use App\User\UserManager;
use Doctrine\ORM\EntityManager;

class TicketManager
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
     * @param int $id
     * @return Ticket|null
     */
    public function findTicketById(int $id)
    {
        $ticket = $this->em->getRepository(Ticket::class)->find($id);
        return $ticket;
    }

    /**
     * @param User   $user
     * @param int    $type
     * @param string $message
     * @return Ticket
     */
    public function create(User $user, int $type, string $message)
    {
        //todo
    }

    /**
     * @param Ticket $ticket
     */
    public function archive(Ticket $ticket)
    {
        $ticket->setStatus(Ticket::STATUS_ARCHIVE);
        $this->em->persist($ticket);
        $this->em->flush($ticket);
    }

}
