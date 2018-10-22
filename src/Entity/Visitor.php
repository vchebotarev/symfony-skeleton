<?php

namespace App\Entity;

use App\Doctrine\Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @todo unique hash
 * @ORM\Table(name="visitor")
 * @ORM\Entity(repositoryClass="App\Repository\VisitorRepository")
 */
class Visitor
{
    use Column\Id;

    use Column\Hash;

    //todo dateCreated

}
