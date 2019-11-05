<?php

namespace App\Doctrine\Column;

use Doctrine\ORM\Mapping as ORM;

trait SortIndex
{
    /**
     * @var int
     * @ORM\Column(name="sort_index", type="smallint", options={"default": 0})
     */
    protected $sortIndex = 0;

    public function getSortIndex() : int
    {
        return $this->sortIndex;
    }

    public function setSortIndex(int $sortIndex)
    {
        $this->sortIndex = $sortIndex;
        return $this;
    }
}
