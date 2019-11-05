<?php

namespace App\Doctrine\Column;

use App\Doctrine\Entity\User as UserEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Использовать только в том случае если вам подходит nullable=false, onDelete="CASCADE"
 */
trait User
{
    /**
     * @var UserEntity
     * @ORM\ManyToOne(targetEntity="App\Doctrine\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $user;

    public function getUser() : UserEntity
    {
        return $this->user;
    }

    public function setUser(UserEntity $user)
    {
        $this->user = $user;
        return $this;
    }
}
