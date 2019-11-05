<?php

namespace App\Doctrine\Column;

use Doctrine\ORM\Mapping as ORM;

trait Id
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    public function getId() : int
    {
        return $this->id;
    }
}
