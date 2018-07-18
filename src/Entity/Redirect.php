<?php

namespace App\Entity;

use App\Doctrine\Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RedirectRepository")
 * @ORM\Table(name="redirect")
 */
class Redirect
{
    use Column\Id;

    protected $link;

    protected $user;

    protected $is_unique;

    protected $url_in;
    protected $url_from;
    protected $url_redirect;

    use Column\Ip;

    use Column\DateCreated;

    use Column\SubidKeyword;

}
