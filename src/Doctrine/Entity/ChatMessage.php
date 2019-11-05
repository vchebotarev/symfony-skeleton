<?php

namespace App\Doctrine\Entity;

use App\Doctrine\Column as Column;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Doctrine\Repository\ChatMessageRepository")
 * @ORM\Table(name="chat_message")
 * @ORM\HasLifecycleCallbacks
 */
class ChatMessage
{
    use Column\Id;

    use Column\Body;

    use Column\User;

    use Column\DateCreated;

    /**
     * @var Chat
     * @ORM\ManyToOne(targetEntity="App\Doctrine\Entity\Chat", inversedBy="messages")
     * @ORM\JoinColumn(name="chat_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $chat;

    /**
     * @var ChatMessageUser[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Doctrine\Entity\ChatMessageUser", mappedBy="message")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getChat() : Chat
    {
        return $this->chat;
    }

    public function setChat(Chat $chat)
    {
        $this->chat = $chat;
        return $this;
    }

    /**
     * @return ChatMessageUser[]|ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
