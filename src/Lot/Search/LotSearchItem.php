<?php

namespace App\Lot\Search;

use App\Entity\Lot;
use App\Entity\LotBet;
use App\Entity\User;

class LotSearchItem
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var float
     */
    protected $priceStart;

    /**
     * @var float
     */
    protected $priceBlitz;

    /**
     * @var int
     */
    protected $betCount = 0;

    /**
     * @var LotBet|null
     */
    protected $betLast;

    /**
     * @var \DateTime
     */
    protected $dateCreated;

    /**
     * @var \DateTime|null
     */
    protected $dateStarted;

    /**
     * @var \DateTime|null
     */
    protected $dateFinished;

    /**
     * @param Lot $lot
     */
    public function __construct(Lot $lot)
    {
        $this->id           = $lot->getId();
        $this->name         = $lot->getName();
        $this->body         = $lot->getBody();
        $this->status       = $lot->getStatus();
        $this->priceStart   = $lot->getPriceStart();
        $this->priceBlitz   = $lot->getPriceBlitz();
        $this->dateCreated  = $lot->getDateCreated();
        $this->dateStarted  = $lot->getDateStarted();
        $this->dateFinished = $lot->getDateFinished();

        //только при условии, что они инициализированы
        $this->user    = $lot->getUser();
        $this->betLast = $lot->getBetLast();
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getBody() : string
    {
        return $this->body;
    }

    /**
     * @return int
     */
    public function getStatus() : int
    {
        return $this->status;
    }

    /**
     * @return User
     */
    public function getUser() : User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return float
     */
    public function getPriceStart() : float
    {
        return $this->priceStart;
    }

    /**
     * @return float
     */
    public function getPriceBlitz() : float
    {
        return $this->priceBlitz;
    }

    /**
     * @return int
     */
    public function getBetCount() : int
    {
        return $this->betCount;
    }

    /**
     * @param int $betCount
     * @return $this
     */
    public function setBetCount(int $betCount)
    {
        $this->betCount = $betCount;
        return $this;
    }

    /**
     * @return LotBet
     */
    public function getBetLast() : ?LotBet
    {
        return $this->betLast;
    }

    /**
     * @param LotBet $betLast
     * @return $this
     */
    public function setBetLast(LotBet $betLast)
    {
        $this->betLast = $betLast;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreated() : \DateTime
    {
        return $this->dateCreated;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateStarted() : ?\DateTime
    {
        return $this->dateStarted;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateFinished() : ?\DateTime
    {
        return $this->dateFinished;
    }

}
