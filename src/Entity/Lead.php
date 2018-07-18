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
    const STATUS_NONE    = 0;
    const DECLINED       = 4;
    const APPROVED       = 1;
    const APPROVED_FINAL = 2;

    use Column\Id;

    /**
     * @var Link
     */
    protected $link;



    protected $redirect_id;

    protected $adv_id;
    protected $pub_id;

    protected $offer_id;
    protected $goal_id;

    use Column\SubidKeyword;

    use Column\DateCreated; //todo other dates

    protected $user_agent;



}
