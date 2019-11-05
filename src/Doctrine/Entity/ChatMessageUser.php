<?php

namespace App\Doctrine\Entity;

use App\Doctrine\Column as Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Doctrine\Repository\ChatMessageUserRepository")
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
     * @ORM\ManyToOne(targetEntity="App\Doctrine\Entity\ChatMessage", inversedBy="users")
     * @ORM\JoinColumn(name="message_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $message;

    public function getMessage() : ChatMessage
    {
        return $this->message;
    }

    public function setMessage(ChatMessage $message)
    {
        $this->message = $message;
        return $this;
    }
}
