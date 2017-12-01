<?php

namespace App\Entity;

use App\Doctrine\Column as Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketMessageRepository")
 * @ORM\Table(name="ticket_message")
 * @ORM\HasLifecycleCallbacks
 */
class TicketMessage
{
    use Column\Id;

    /**
     * @var Ticket
     * @ORM\ManyToOne(targetEntity="App\Entity\Ticket", inversedBy="messages")
     * @ORM\JoinColumn(name="ticket_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $ticket;

    use Column\User;

    use Column\IsRead;

    use Column\Body;

    use Column\DateCreated;

    /**
     * @return Ticket
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * @param Ticket $ticket
     * @return $this
     */
    public function setTicket(Ticket $ticket)
    {
        $this->ticket = $ticket;
        return $this;
    }

}
