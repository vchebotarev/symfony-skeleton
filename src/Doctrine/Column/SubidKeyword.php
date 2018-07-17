<?php

namespace App\Doctrine\Column;

use App\Entity\Subid;
use Doctrine\ORM\Mapping as ORM;

trait SubidKeyword
{
    /**
     * @var Subid|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Subid")
     * @ORM\JoinColumn(name="subid1_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $subid1;
    /**
     * @var Subid|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Subid")
     * @ORM\JoinColumn(name="subid2_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $subid2;
    /**
     * @var Subid|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Subid")
     * @ORM\JoinColumn(name="subid3_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $subid3;
    /**
     * @var Subid|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Subid")
     * @ORM\JoinColumn(name="subid4_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $subid4;
    /**
     * @var Subid|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Subid")
     * @ORM\JoinColumn(name="subid5_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $subid5;

    /**
     * @var Subid|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Subid")
     * @ORM\JoinColumn(name="keyword_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $keyword;

    /**
     * @return Subid|null
     */
    public function getSubid1() : ?Subid
    {
        return $this->subid1;
    }

    /**
     * @param Subid|null $subid1
     * @return $this
     */
    public function setSubid1(?Subid $subid1)
    {
        $this->subid1 = $subid1;
        return $this;
    }

    /**
     * @return Subid|null
     */
    public function getSubid2() : ?Subid
    {
        return $this->subid2;
    }

    /**
     * @param Subid|null $subid2
     * @return $this
     */
    public function setSubid2(?Subid $subid2)
    {
        $this->subid2 = $subid2;
        return $this;
    }

    /**
     * @return Subid|null
     */
    public function getSubid3() : ?Subid
    {
        return $this->subid3;
    }

    /**
     * @param Subid|null $subid3
     * @return $this
     */
    public function setSubid3(?Subid $subid3)
    {
        $this->subid3 = $subid3;
        return $this;
    }

    /**
     * @return Subid|null
     */
    public function getSubid4() : ?Subid
    {
        return $this->subid4;
    }

    /**
     * @param Subid|null $subid4
     * @return $this
     */
    public function setSubid4(?Subid $subid4)
    {
        $this->subid4 = $subid4;
        return $this;
    }

    /**
     * @return Subid|null
     */
    public function getSubid5() : ?Subid
    {
        return $this->subid5;
    }

    /**
     * @param Subid|null $subid5
     * @return $this
     */
    public function setSubid5(?Subid $subid5)
    {
        $this->subid5 = $subid5;
        return $this;
    }

    /**
     * @return Subid|null
     */
    public function getKeyword() : ?Subid
    {
        return $this->keyword;
    }

    /**
     * @param Subid|null $keyword
     * @return $this
     */
    public function setKeyword(?Subid $keyword)
    {
        $this->keyword = $keyword;
        return $this;
    }

}
