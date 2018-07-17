<?php

namespace App\Entity;

use App\Doctrine\Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OfferRepository")
 * @ORM\Table(name="offer")
 */
class Offer
{
    use Column\Id;

}
