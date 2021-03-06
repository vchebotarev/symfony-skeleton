<?php

namespace App\Doctrine\Column;

use Doctrine\ORM\Mapping as ORM;

trait Ip
{
    /**
     * @var int
     * @ORM\Column(name="ip", type="bigint", options={"default": 0})
     */
    protected $ip = 0;

    public function getIp() : string
    {
        return long2ip($this->ip);
    }

    public function setIp(string $ip)
    {
        $this->ip = ip2long($ip);
        return $this;
    }
}
