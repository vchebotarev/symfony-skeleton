<?php

namespace App\Doctrine\Column;

use App\Entity\Lot as LotEntity;
use Doctrine\ORM\Mapping as ORM;

trait Lot
{
    /**
     * @var LotEntity
     * @ORM\ManyToOne(targetEntity="App\Entity\Lot")
     * @ORM\JoinColumn(name="lot_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $lot;

    /**
     * @return LotEntity
     */
    public function getLot()
    {
        return $this->lot;
    }

    /**
     * @param LotEntity $lot
     * @return $this
     */
    public function setLot(LotEntity $lot)
    {
        $this->lot = $lot;
        return $this;
    }

}
