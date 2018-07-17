<?php

namespace App\Doctrine\Column;

use App\Entity\Offer as OfferEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Использовать только в том случае если вам подходит nullable=false, onDelete="CASCADE"
 */
trait Offer
{
    /**
     * @var OfferEntity
     * @ORM\ManyToOne(targetEntity="App\Entity\Offer")
     * @ORM\JoinColumn(name="offer_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $offer;

    /**
     * @return OfferEntity
     */
    public function getOffer() : OfferEntity
    {
        return $this->offer;
    }

    /**
     * @param OfferEntity $offer
     * @return $this
     */
    public function setOffer(OfferEntity $offer)
    {
        $this->offer = $offer;
        return $this;
    }

}
