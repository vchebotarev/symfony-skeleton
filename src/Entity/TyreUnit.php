<?php

namespace App\Entity;

use App\Doctrine\Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TyreUnitRepository")
 * @ORM\Table(name="tyre_unit")
 */
class TyreUnit
{
    use Column\Id;

    /**
     * @var Tyre
     */
    protected $tyre;

    //ширина
    //высота
    //радиус
    //индекс нагрузки
    //индекс скорости

    //евро стандарт топливо
    //евро стандарт торможение
    //евро стандарт шума

    //страна производитель






}
