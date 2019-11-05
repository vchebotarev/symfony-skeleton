<?php

namespace App\Doctrine\Entity;

use App\Doctrine\Column as Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Doctrine\Repository\ChatUserRepository")
 * @ORM\Table(name="chat_user")
 */
class ChatUser
{
    use Column\Id;

    use Column\User;

    /**
     * @var Chat
     * @ORM\ManyToOne(targetEntity="App\Doctrine\Entity\Chat", inversedBy="users")
     * @ORM\JoinColumn(name="chat_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $chat;

    public function getChat() : Chat
    {
        return $this->chat;
    }

    public function setChat(Chat $chat)
    {
        $this->chat = $chat;
        return $this;
    }

}
