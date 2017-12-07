<?php

namespace App\Lot\Bet;

use App\Entity\Lot;
use App\Entity\LotBet;
use App\Lot\LotManager;
use App\Lot\LotTimeManager;
use App\User\UserManager;
use Doctrine\ORM\EntityManager;

class LotBetManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var LotManager
     */
    protected $lotManager;

    /**
     * @var LotTimeManager
     */
    protected $lotTimeManager;

    /**
     * @param EntityManager  $em
     * @param UserManager    $userManager
     * @param LotManager     $lotManager
     * @param LotTimeManager $lotTimeManager
     */
    public function __construct(EntityManager $em, UserManager $userManager, LotManager $lotManager, LotTimeManager $lotTimeManager)
    {
        $this->em             = $em;
        $this->userManager    = $userManager;
        $this->lotManager     = $lotManager;
        $this->lotTimeManager = $lotTimeManager;
    }

    /**
     * @param Lot   $lot
     * @param float $betPrice
     * @return LotBet
     */
    public function create(Lot $lot, float $betPrice) : LotBet
    {
        $lotBet = new LotBet();
        $lotBet->setLot($lot);
        $lotBet->setPrice($betPrice);
        $lotBet->setUser($this->userManager->getCurrentUser());

        $lot->setBetLast($lotBet);
        $lot->setDateFinished($this->lotTimeManager->getDateCloseOnLotBetCreate($lot->getDateFinished()));
        $lot->getBets()->add($lotBet);

        $this->em->persist($lotBet);
        $this->em->flush($lotBet);
        $this->em->persist($lot);
        $this->em->flush($lot);

        if ($lot->getPriceBlitz() && $betPrice == $lot->getPriceBlitz()) {
            $this->lotManager->close($lot);
        }
        return $lotBet;
    }

    /**
     * @param LotBet $lotBet
     */
    public function deleteBet(LotBet $lotBet)
    {
        $lot = $lotBet->getLot();

        $this->em->remove($lotBet);
        $this->em->flush($lotBet);

        $lastBet = $this->em->getRepository(LotBet::class)->findLastByLot($lot);
        $lot->setBetLast($lastBet);
        $this->em->persist($lot);
        $this->em->flush($lot);
    }

}
