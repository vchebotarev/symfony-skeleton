<?php

namespace App\Doctrine\Column;

use Doctrine\ORM\Mapping as ORM;

trait Name
{
    /**
     * @var string
     * @ORM\Column(name="name", type="string", options={"default": ""})
     */
    protected $name = '';

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

}
