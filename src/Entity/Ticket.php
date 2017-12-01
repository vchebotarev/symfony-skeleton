<?php

namespace App\Entity;

use App\Doctrine\Column as Column;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 * @ORM\Table(name="ticket")
 * @ORM\HasLifecycleCallbacks
 */
class Ticket
{
    const STATUS_ACTIVE  = 1;
    const STATUS_ARCHIVE = 2;

    const TYPE_INFO     = 1;
    const TYPE_QUESTION = 2;
    const TYPE_ERROR    = 3;

    use Column\Id;

    use Column\User; //пользователь кому адресован тикет

    use Column\Status;

    use Column\Type;

    use Column\Name;

    /**
     * @var TicketMessage[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\TicketMessage", mappedBy="ticket")
     */
    protected $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    /**
     * @return TicketMessage[]|ArrayCollection
     */
    public function getMessages()
    {
        return $this->messages;
    }

}
