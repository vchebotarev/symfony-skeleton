<?php

namespace AppBundle\Entity;

use App\Doctrine\Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @todo unique hash
 * @ORM\Table(name="user_agent")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserAgentRepository")
 */
class UserAgent
{
    use Column\Id;

    use Column\Hash;

    use Column\Name;

    //todo more info

}