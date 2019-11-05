<?php

namespace App\Chat\Search;

use App\Chat\Message\Search\ChatMessageSearchItem;
use App\Doctrine\Entity\Chat;
use App\Doctrine\Entity\User;

class ChatSearchItem
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $countUnread = 0;

    /**
     * @var ChatMessageSearchItem|null
     */
    protected $lastMessage;

    /**
     * @var User[]
     */
    protected $users;

    /**
     * @param Chat|null $chat
     */
    public function __construct(Chat $chat = null)
    {
        if (!$chat) {
            return;
        }
        $this->setId($chat->getId());
        $this->setName($chat->getName());
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getCountUnread() : int
    {
        return $this->countUnread;
    }

    /**
     * @param int $countUnread
     * @return $this
     */
    public function setCountUnread(int $countUnread)
    {
        $this->countUnread = $countUnread;
        return $this;
    }

    /**
     * @return ChatMessageSearchItem|null
     */
    public function getLastMessage()
    {
        return $this->lastMessage;
    }

    /**
     * @param ChatMessageSearchItem $lastMessage
     * @return $this
     */
    public function setLastMessage(ChatMessageSearchItem $lastMessage)
    {
        $this->lastMessage = $lastMessage;
        return $this;
    }

    /**
     * @return User[]
     */
    public function getUsers() : array
    {
        return $this->users;
    }

    /**
     * @param User[] $users
     * @return $this
     */
    public function setUsers($users)
    {
        $this->users = $users;
        return $this;
    }
}
