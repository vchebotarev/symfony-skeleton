<?php

namespace App\Doctrine\Column;

use Doctrine\ORM\Mapping as ORM;
use App\Doctrine\Entity\UserAgent as UserAgentEntity;

/**
 * Использовать только в том случае если вам подходит nullable=false, onDelete="CASCADE"
 */
trait UserAgent
{
    /**
     * @var UserAgentEntity
     * @ORM\ManyToOne(targetEntity="App\Doctrine\Entity\UserAgent")
     * @ORM\JoinColumn(name="user_agent_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $userAgent;

    public function getUserAgent() : UserAgentEntity
    {
        return $this->userAgent;
    }

    public function setUserAgent(UserAgentEntity $userAgent)
    {
        $this->userAgent = $userAgent;
        return $this;
    }
}
