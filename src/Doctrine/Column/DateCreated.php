<?php

namespace App\Doctrine\Column;

use Doctrine\ORM\Mapping as ORM;

/**
 * Не забываем в моделе о @ORM\HasLifecycleCallbacks
 */
trait DateCreated
{
    /**
     * @var \DateTime
     * @ORM\Column(name="date_created", type="datetimetz")
     */
    protected $dateCreated;

    /**
     * @param \DateTime $dateCreated
     * @return $this
     */
    public function setDateCreated(\DateTime $dateCreated)
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersistDateCreated()
    {
        if (!$this->getDateCreated()) {
            $this->setDateCreated(new \DateTime('now'));
        }
    }

}
