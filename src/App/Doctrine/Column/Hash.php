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

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     * @return $this
     */
    public function setHash(string $hash)
    {
        $this->hash = $hash;
        return $this;
    }

}
