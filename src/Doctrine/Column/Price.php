<?php

namespace App\Doctrine\Column;

use Doctrine\ORM\Mapping as ORM;

trait Price
{
    /**
     * @var float
     * @ORM\Column(name="price", type="decimal", precision=15, scale=2, options={"default": 0})
     */
    protected $price = 0;

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

}
