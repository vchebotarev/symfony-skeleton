<?php

namespace App\Doctrine\Column;

use Doctrine\ORM\Mapping as ORM;

trait Data
{
    /**
     * @var array
     * @ORM\Column(name="data", type="json")
     */
    protected $data = [];

    public function getData() : array
    {
        return $this->data;
    }

    /**
     * @todo \JsonSerializable as param
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

}
