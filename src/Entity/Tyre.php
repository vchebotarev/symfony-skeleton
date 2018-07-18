<?php

namespace App\Entity;

use App\Doctrine\Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TyreRepository")
 * @ORM\Table(name="tyre")
 */
class Tyre
{
    use Column\Id;

    /**
     * @var TyreBrand
     */
    protected $brand;

    /**
     * @var int
     */
    protected $season; //зима, лето, всесезонка

    /**
     * @var bool
     */
    protected $isSpikes;

    /**
     * @var bool
     */
    protected $isRunFlat;

    /**
     * @var bool
     */
    protected $isSeal; //затягивает мелкие проколы

    /**
     * @var int
     */
    protected $autoType; //легковая, внедорожник, легкогрузовая, запаска, прицеп

    /**
     * @var bool
     */
    protected $orientation; //симетричная или направленная

}
