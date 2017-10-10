<?php

namespace App\Doctrine\Column;

use Doctrine\ORM\Mapping as ORM;

trait IsDeleted
{
    /**
     * @var bool
     * @ORM\Column(name="is_deleted", type="boolean", options={"default": false})
     */
    protected $isDeleted = false;

    /**
     * @return bool
     */
    public function isDeleted() : bool
    {
        return $this->isDeleted;
    }

    /**
     * @param bool $isDeleted
     * @return $this
     */
    public function setIsDeleted(bool $isDeleted)
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }

}
