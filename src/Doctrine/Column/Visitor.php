<?php

namespace App\Doctrine\Column;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Visitor as VisitorEntity;

/**
 * Использовать только в том случае если вам подходит nullable=false, onDelete="CASCADE"
 */
trait Visitor
{
    /**
     * @var VisitorEntity
     * @ORM\ManyToOne(targetEntity="App\Entity\Visitor")
     * @ORM\JoinColumn(name="visitor_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $visitor;

    public function getVisitor() : VisitorEntity
    {
        return $this->visitor;
    }

    public function setVisitor(VisitorEntity $visitor)
    {
        $this->visitor = $visitor;
        return $this;
    }

}
