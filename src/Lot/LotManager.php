<?php

namespace App\Lot;

use App\Entity\Lot;
use Doctrine\ORM\EntityManager;

class LotManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var LotTimeManager
     */
    protected $lotTimeManager;

    /**
     * @param EntityManager  $em
     * @param LotTimeManager $lotTimeManager
     */
    public function __construct(EntityManager $em, LotTimeManager $lotTimeManager)
    {
        $this->em             = $em;
        $this->lotTimeManager = $lotTimeManager;
    }

    /**
     * @param Lot   $lot
     * @param float $price
     * @param float $priceBlitz
     */
    public function start(Lot $lot, float $price, float $priceBlitz = 0)
    {
        $dateClose = $this->lotTimeManager->getDateCloseOnLotStart();

        $lot->setDateFinished($dateClose);
        $lot->setStatus(Lot::STATUS_ACTIVE);
        $lot->setDateStarted(new \DateTime());
        $lot->setPriceStart($price);
        $lot->setPriceBlitz($priceBlitz);

        $this->em->persist($lot);
        $this->em->flush($lot);
    }

    /**
     * @param Lot $lot
     */
    public function close(Lot $lot)
    {
        $lot->setStatus(Lot::STATUS_CLOSED);

        $this->em->persist($lot);
        $this->em->flush($lot);
    }

}
