<?php

namespace App\Doctrine\Column;

use Doctrine\ORM\Mapping as ORM;

trait Body
{
    /**
     * @var string
     * @ORM\Column(name="body", type="text", options={"default": ""}))
     */
    protected $body = '';

    /**
     * @return string
     */
    public function getBody() : string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBody(string $body)
    {
        $this->body = $body;
        return $this;
    }

}
