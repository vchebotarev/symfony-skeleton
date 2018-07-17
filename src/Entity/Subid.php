<?php

namespace App\Entity;

use App\Doctrine\Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubidRepository")
 * @ORM\Table(name="subid")
 */
class Subid
{
    const TYPE_SUBID1  = 1;
    const TYPE_SUBID2  = 2;
    const TYPE_SUBID3  = 3;
    const TYPE_SUBID4  = 4;
    const TYPE_SUBID5  = 5;
    const TYPE_KEYWORD = 6;

    use Column\Id;

    use Column\Type;

    //todo ограничение длины
    use Column\Name;

    //todo мб хеш + index?

}
