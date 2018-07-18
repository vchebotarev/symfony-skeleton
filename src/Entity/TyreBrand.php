<?php

namespace App\Entity;

use App\Doctrine\Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TyreBrandRepository")
 * @ORM\Table(name="tyre_brand")
 */
class TyreBrand
{
    use Column\Id;

    use Column\Name;



}
