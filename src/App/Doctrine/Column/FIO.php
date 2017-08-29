<?php

namespace App\Doctrine\Column;

use Doctrine\ORM\Mapping as ORM;

trait FIO
{
    /**
     * @var string
     * @ORM\Column(name="fio_f", type="string", options={"default": ""})
     */
    protected $fioF = '';

    /**
     * @var string
     * @ORM\Column(name="fio_i", type="string", options={"default": ""})
     */
    protected $fioI = '';

    /**
     * @var string
     * @ORM\Column(name="fio_o", type="string", options={"default": ""})
     */
    protected $fioO = '';

    /**
     * @return string
     */
    public function getFioF(): string
    {
        return $this->fioF;
    }

    /**
     * @param string $fioF
     * @return $this
     */
    public function setFioF(string $fioF)
    {
        $this->fioF = $fioF;
        return $this;
    }

    /**
     * @return string
     */
    public function getFioI(): string
    {
        return $this->fioI;
    }

    /**
     * @param string $fioI
     * @return $this
     */
    public function setFioI(string $fioI)
    {
        $this->fioI = $fioI;
        return $this;
    }

    /**
     * @return string
     */
    public function getFioO(): string
    {
        return $this->fioO;
    }

    /**
     * @param string $fioO
     * @return $this
     */
    public function setFioO(string $fioO)
    {
        $this->fioO = $fioO;
        return $this;
    }

}
