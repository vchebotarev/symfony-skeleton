<?php

namespace App\Entity;

use App\Doctrine\Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LinkRepository")
 * @ORM\Table(name="link")
 */
class Link
{
    use Column\Id;

    use Column\Offer;

    use Column\User;

    use Column\Hash; //todo short

    use Column\Data;

}
