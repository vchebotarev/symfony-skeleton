<?php

namespace App\Doctrine\Column;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\UserAgent as UserAgentEntity;

/**
 * Использовать только в том случае если вам подходит nullable=false, onDelete="CASCADE"
 */
trait UserAgent
{
    /**
     * @var UserAgentEntity
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\UserAgent")
     * @ORM\JoinColumn(name="user_agent_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $userAgent;

    /**
     * @return UserAgentEntity
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param UserAgentEntity $userAgent
     * @return $this
     */
    public function setUserAgent(UserAgentEntity $userAgent)
    {
        $this->userAgent = $userAgent;
        return $this;
    }

}
