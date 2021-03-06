<?php

namespace App\Doctrine\Entity;

use App\Doctrine\Column as Column;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Doctrine\Repository\ChatRepository")
 * @ORM\Table(name="chat")
 * @ORM\HasLifecycleCallbacks
 */
class Chat
{
    use Column\Id;

    use Column\Name;

     /**
     * @var ChatMessage[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Doctrine\Entity\ChatMessage", mappedBy="chat")
     */
    private $messages;

    /**
     * @var ChatUser[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Doctrine\Entity\ChatUser", mappedBy="chat")
     */
    private $users;

    public function __construct()
    {
        $this->users    = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    /**
     * @return ChatMessage[]|ArrayCollection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @return ChatUser[]|ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
