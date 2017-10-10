<?php

namespace App\Entity;

use App\Doctrine\Column as Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChatMessageUserRepository")
 * @ORM\Table(name="chat_message_user")
 */
class ChatMessageUser
{
    use Column\Id;

    use Column\IsDeleted;

    use Column\IsRead;

    use Column\User;

    /**
     * @var ChatMessage
     * @ORM\ManyToOne(targetEntity="App\Entity\ChatMessage", inversedBy="users")
     * @ORM\JoinColumn(name="message_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $message;

    /**
     * @return ChatMessage
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param ChatMessage $message
     * @return $this
     */
    public function setMessage(ChatMessage $message)
    {
        $this->message = $message;
        return $this;
    }

}
