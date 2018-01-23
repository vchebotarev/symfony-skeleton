<?php

namespace App\Doctrine\Column;

use Doctrine\ORM\Mapping as ORM;

/**
 * Не забываем в моделе о @ORM\HasLifecycleCallbacks
 */
trait DateUpdated
{
    /**
     * @var \DateTime
     * @ORM\Column(name="date_updated", type="datetimetz")
     */
    protected $dateUpdated;

    /**
     * @param \DateTime $dateUpdated
     * @return $this
     */
    public function setDateUpdated(\DateTime $dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function onPrePersistPreUpdateDateUpdated()
    {
        $this->setDateUpdated(new \DateTime('now'));
    }

}
