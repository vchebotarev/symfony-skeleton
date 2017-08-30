<?php

namespace App\Doctrine\Column;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Visitor as VisitorEntity;

/**
 * Использовать только в том случае если вам подходит nullable=false, onDelete="CASCADE"
 */
trait Visitor
{
    /**
     * @var VisitorEntity
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Visitor")
     * @ORM\JoinColumn(name="visitor_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $visitor;

    /**
     * @return VisitorEntity
     */
    public function getVisitor()
    {
        return $this->visitor;
    }

    /**
     * @param VisitorEntity $visitor
     * @return $this
     */
    public function setVisitor(VisitorEntity $visitor)
    {
        $this->visitor = $visitor;
        return $this;
    }

}
