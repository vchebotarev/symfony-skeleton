<?php

namespace App\Entity;

use App\Doctrine\Column as Column;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChatRepository")
 * @ORM\Table(name="chat")
 * @ORM\HasLifecycleCallbacks
 */
class Chat
{
    use Column\Id;

    use Column\Name;

     /**
     * @var ChatMessage[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\ChatMessage", mappedBy="chat")
     */
    protected $messages;

    /**
     * @var ChatUser[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\ChatUser", mappedBy="chat")
     */
    protected $users;

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
