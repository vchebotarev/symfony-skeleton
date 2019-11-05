<?php

namespace App\Doctrine\Column;

use Doctrine\ORM\Mapping as ORM;

trait IsRead
{
    /**
     * @var bool
     * @ORM\Column(name="is_read", type="boolean", options={"default": false})
     */
    protected $isRead = false;

    public function isRead() : bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead)
    {
        $this->isRead = $isRead;
        return $this;
    }
}
