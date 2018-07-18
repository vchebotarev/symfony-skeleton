<?php

namespace App\Entity;

use App\Doctrine\Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OfferUserRepository")
 * @ORM\Table(name="offer_user")
 */
class OfferUser
{
    use Column\Id;

    /**
     * @var Offer|null
     */
    protected $offer;

    use Column\User;

    use Column\Name;

    use Column\IsDeleted;

    //todo description

    //todo

}
