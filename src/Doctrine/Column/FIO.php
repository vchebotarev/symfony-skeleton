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

    public function getFioF() : string
    {
        return $this->fioF;
    }

    public function setFioF(string $fioF)
    {
        $this->fioF = $fioF;
        return $this;
    }

    public function getFioI() : string
    {
        return $this->fioI;
    }

    public function setFioI(string $fioI)
    {
        $this->fioI = $fioI;
        return $this;
    }

    public function getFioO() : string
    {
        return $this->fioO;
    }

    public function setFioO(string $fioO)
    {
        $this->fioO = $fioO;
        return $this;
    }
}
