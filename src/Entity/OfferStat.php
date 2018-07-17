<?php

namespace App\Entity;

use App\Doctrine\Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OfferStatRepository")
 * @ORM\Table(name="redirect")
 */
class OfferStat
{
    use Column\Id;

    use Column\Offer;

    /* @todo
    редиректы, лиды, лившие
    лучшие и худшие показатели из ливших + разбиение кто лил много и кто лил мало
    всего, за неделю, за месяц, за прошлый месяц
     */

}
