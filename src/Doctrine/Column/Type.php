<?php

namespace App\Doctrine\Column;

use Doctrine\ORM\Mapping as ORM;

trait Type
{
    /**
     * @var int
     * @ORM\Column(name="type", type="smallint", options={"default": 0}))
     */
    protected $type = 0;

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return $this
     */
    public function setType(int $type)
    {
        $this->type = $type;
        return $this;
    }

}
