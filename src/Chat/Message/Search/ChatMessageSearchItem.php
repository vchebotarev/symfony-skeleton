<?php

namespace App\Chat\Message\Search;

use App\Doctrine\Entity\ChatMessage;
use App\Doctrine\Entity\User;

class ChatMessageSearchItem
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var \DateTime
     */
    protected $dateCreated;

    /**
     * @var User
     */
    protected $user;

    /**
     * Если моё - прочитан ли собеседником, если не моё - прочитано ли мной
     * @var bool
     */
    protected $isRead = true;

    /**
     * @param ChatMessage $chatMessage
     */
    public function __construct(ChatMessage $chatMessage = null)
    {
        if (!$chatMessage) {
            return;
        }
        $this->setId($chatMessage->getId());
        $this->setBody($chatMessage->getBody());
        $this->setDateCreated($chatMessage->getDateCreated());
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
    public function getBody() : string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreated() : \DateTime
    {
        return $this->dateCreated;
    }

    /**
     * @param \DateTime $dateCreated
     * @return $this
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser() : User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRead() : bool
    {
        return $this->isRead;
    }

    /**
     * @param bool $isRead
     * @return $this
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;
        return $this;
    }
}
