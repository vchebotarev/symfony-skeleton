<?php

namespace App\Twig\Extension;

use App\Chat\ChatCounter;
use App\User\UserManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ChatExtension extends AbstractExtension
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var ChatCounter
     */
    protected $chatCounter;

    /**
     * @param ChatCounter $chatCounter
     * @param UserManager $userManager
     */
    public function __construct(ChatCounter $chatCounter, UserManager $userManager)
    {
        $this->chatCounter = $chatCounter;
        $this->userManager = $userManager;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('count_chat_unread', [$this, 'countChatUnread']),
            new TwigFunction('count_chat_message_unread', [$this, 'countMessageUnread']),
        ];
    }

    /**
     * @return int
     */
    public function countChatUnread() : int
    {
        return $this->chatCounter->countUnreadChats($this->userManager->getCurrentUser());
    }

    /**
     * @return int
     */
    public function countMessageUnread() : int
    {
        return $this->chatCounter->countUnreadMessages($this->userManager->getCurrentUser());
    }
}
