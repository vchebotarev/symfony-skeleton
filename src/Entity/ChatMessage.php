<?php

namespace App\Entity;

use App\Doctrine\Column as Column;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChatMessageRepository")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Chat", inversedBy="messages")
     * @ORM\JoinColumn(name="chat_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $chat;

    /**
     * @var ChatMessageUser[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\ChatMessageUser", mappedBy="message")
     */
    protected $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

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

    /**
     * @return ChatMessageUser[]|ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

}
