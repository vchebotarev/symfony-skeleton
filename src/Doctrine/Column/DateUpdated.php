<?php

namespace App\Doctrine\Column;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Не забываем в моделе о @ORM\HasLifecycleCallbacks
 */
trait DateUpdated
{
    /**
     * @var DateTime
     * @ORM\Column(name="date_updated", type="datetimetz", options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $dateUpdated;

    public function getDateUpdated() : DateTime
    {
        return $this->dateUpdated;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function onPrePersistPreUpdateDateUpdated()
    {
        $this->dateUpdated = new DateTime('now');
    }
}
