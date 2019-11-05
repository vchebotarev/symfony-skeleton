<?php

namespace App\Doctrine\Column;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Не забываем в моделе о @ORM\HasLifecycleCallbacks
 */
trait DateCreated
{
    /**
     * @var DateTime
     * @ORM\Column(name="date_created", type="datetimetz", options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $dateCreated;

    public function getDateCreated() : DateTime
    {
        return $this->dateCreated;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersistDateCreated()
    {
        $this->dateCreated = new DateTime('now');
    }
}
