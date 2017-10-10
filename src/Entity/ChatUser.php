<?php

namespace App\Entity;

use App\Doctrine\Column as Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChatUserRepository")
 * @ORM\Table(name="chat_user")
 */
class ChatUser
{
    use Column\Id;

    use Column\User;

    /**
     * @var Chat
     * @ORM\ManyToOne(targetEntity="App\Entity\Chat", inversedBy="users")
     * @ORM\JoinColumn(name="chat_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $chat;

    /**
     * @return Chat
     */
    public function getChat()
    {
        return $this->chat;
    }

    /**
     * @param Chat $chat
     * @return $this
     */
    public function setChat(Chat $chat)
    {
        $this->chat = $chat;
        return $this;
    }

}
