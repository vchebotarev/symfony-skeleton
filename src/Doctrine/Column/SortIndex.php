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

    /**
     * @return int
     */
    public function getSortIndex() : int
    {
        return $this->sortIndex;
    }

    /**
     * @param int $sortIndex
     * @return $this
     */
    public function setSortIndex(int $sortIndex)
    {
        $this->sortIndex = $sortIndex;
        return $this;
    }

}
