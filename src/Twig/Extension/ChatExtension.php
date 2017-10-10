<?php

namespace App\Twig\Extension;

use App\Chat\ChatCounter;
use App\User\UserManager;

class ChatExtension extends \Twig_Extension
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

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('count_chat_unread', [$this, 'countChatUnread']),
            new \Twig_SimpleFunction('count_chat_message_unread', [$this, 'countMessageUnread']),
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
