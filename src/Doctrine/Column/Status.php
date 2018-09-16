<?php

namespace App\Doctrine\Column;

use Doctrine\ORM\Mapping as ORM;

trait Status
{
    /**
     * @var int
     * @ORM\Column(name="status", type="smallint", options={"default": 0}))
     */
    protected $status = 0;

    public function getStatus() : int
    {
        return $this->status;
    }

    public function setStatus(int $status)
    {
        $this->status = $status;
        return $this;
    }

}
