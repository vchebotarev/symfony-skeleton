<?php

namespace App\Entity;
use App\Doctrine\Column;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="lot")
 * @ORM\Entity(repositoryClass="App\Repository\LotRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Lot
{
    const STATUS_DRAFT  = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_CLOSED = 2;

    use Column\Id;

    use Column\User;

    use Column\Status;

    use Column\Name;

    use Column\Body;

    /**
     * @var float
     * @ORM\Column(name="price_start", type="decimal", precision=15, scale=2, options={"default": 0})
     */
    protected $priceStart = 0;

    /**
     * @var float
     * @ORM\Column(name="price_blitz", type="decimal", precision=15, scale=2, options={"default": 0})
     */
    protected $priceBlitz = 0;

    /**
     * @var LotBet|null
     * @ORM\ManyToOne(targetEntity="App\Entity\LotBet")
     * @ORM\JoinColumn(name="bet_last_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $betLast;

    use Column\DateCreated;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="date_started", type="datetimetz", nullable=true)
     */
    protected $dateStarted;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="date_finished", type="datetimetz", nullable=true)
     */
    protected $dateFinished;

    /**
     * @var LotBet[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\LotBet", mappedBy="lot")
     */
    protected $bets;

    public function __construct()
    {
        $this->bets = new ArrayCollection();
    }

    /**
     * @return float
     */
    public function getPriceStart() : float
    {
        return $this->priceStart;
    }

    /**
     * @param float $priceStart
     * @return $this
     */
    public function setPriceStart($priceStart)
    {
        $this->priceStart = $priceStart;
        return $this;
    }

    /**
     * @return float
     */
    public function getPriceBlitz() : float
    {
        return $this->priceBlitz;
    }

    /**
     * @param float $priceBlitz
     * @return $this
     */
    public function setPriceBlitz($priceBlitz)
    {
        $this->priceBlitz = $priceBlitz;
        return $this;
    }

    /**
     * @return LotBet|null
     */
    public function getBetLast() : ?LotBet
    {
        return $this->betLast;
    }

    /**
     * @param LotBet|null $betLast
     * @return $this
     */
    public function setBetLast(LotBet $betLast = null)
    {
        $this->betLast = $betLast;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateStarted() : ?\DateTime
    {
        return $this->dateStarted;
    }

    /**
     * @param \DateTime|null $dateStarted
     * @return $this
     */
    public function setDateStarted($dateStarted)
    {
        $this->dateStarted = $dateStarted;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateFinished() : ?\DateTime
    {
        return $this->dateFinished;
    }

    /**
     * @param \DateTime|null $dateFinished
     * @return $this
     */
    public function setDateFinished($dateFinished)
    {
        $this->dateFinished = $dateFinished;
        return $this;
    }

    /**
     * @return LotBet[]|ArrayCollection
     */
    public function getBets()
    {
        return $this->bets;
    }

    /**
     * @return float
     */
    public function getPriceCurrent() : float
    {
        if ($this->betLast) {
            return $this->betLast->getPrice();
        }
        return $this->priceStart;
    }

}
