<?php

namespace App\Doctrine\Entity;

use App\Doctrine\Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @todo unique type + user and type + hash
 * @ORM\Table(name="user_token")
 * @ORM\Entity(repositoryClass="App\Doctrine\Repository\UserTokenRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class UserToken
{
    public const TYPE_REGISTRATION   = 1;
    public const TYPE_RESET_PASSWORD = 2;
    public const TYPE_CHANGE_EMAIL   = 3;

    use Column\Id;

    use Column\Hash;

    use Column\User;

    use Column\Type;

    use Column\Data;

    use Column\DateCreated;

}
