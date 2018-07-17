<?php

namespace App\Entity;

use App\Doctrine\Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LeadRepository")
 * @ORM\Table(name="lead")
 */
class Lead
{
    use Column\Id;

    protected $redirect_id;

    protected $adv_id;
    protected $pub_id;

    protected $offer_id;
    protected $goal_id;

    use Column\SubidKeyword;

    use Column\DateCreated; //todo other dates

    protected $user_agent;



}
