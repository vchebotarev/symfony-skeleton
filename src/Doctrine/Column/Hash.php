<?php

namespace App\Doctrine\Column;

use Doctrine\ORM\Mapping as ORM;

trait Hash
{
    /**
     * @var string
     * @ORM\Column(name="hash", type="string", length=32, options={"default": "", "fixed": true, "collation": "ascii_bin"})
     */
    protected $hash = '';

    public function getHash() : string
    {
        return $this->hash;
    }

    public function setHash(string $hash)
    {
        $this->hash = $hash;
        return $this;
    }

}
