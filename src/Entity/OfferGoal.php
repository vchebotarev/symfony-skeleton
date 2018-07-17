<?php

namespace App\Entity;

use App\Doctrine\Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OfferGoalRepository")
 * @ORM\Table(name="offer_goal")
 */
class OfferGoal
{
    use Column\Id;

    use Column\Offer;

    //todo

}
