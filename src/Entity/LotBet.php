<?php

namespace App\Entity;

use App\Doctrine\Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="lot_bet")
 * @ORM\Entity(repositoryClass="App\Repository\LotBetRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class LotBet
{
    use Column\Id;

    use Column\Lot;

    use Column\User;

    use Column\Price;

    use Column\DateCreated;

}
