<?php

namespace App\Twig\Extension;

use App\Ticket\TicketCounter;
use App\User\UserManager;

class TicketExtension extends \Twig_Extension
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var TicketCounter
     */
    protected $ticketCounter;

    /**
     * @param TicketCounter $ticketCounter
     * @param UserManager   $userManager
     */
    public function __construct(TicketCounter $ticketCounter, UserManager $userManager)
    {
        $this->ticketCounter = $ticketCounter;
        $this->userManager   = $userManager;
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('count_ticket_unread', [$this, 'countTicketUnread']),
            new \Twig_SimpleFunction('count_ticket_message_unread', [$this, 'countMessageUnread']),
        ];
    }

    /**
     * @return int
     */
    public function countTicketUnread() : int
    {
        return $this->ticketCounter->countUnread($this->userManager->getCurrentUser());
    }

    /**
     * @return int
     */
    public function countMessageUnread() : int
    {
        return $this->ticketCounter->countUnreadMessages($this->userManager->getCurrentUser());
    }

}
