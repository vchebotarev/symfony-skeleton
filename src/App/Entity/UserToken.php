<?php

namespace App\Entity;

use App\Doctrine\Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @todo unique type + user and type + hash
 * @ORM\Table(name="user_token")
 * @ORM\Entity(repositoryClass="App\Repository\UserTokenRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class UserToken
{
    const TYPE_REGISTRATION   = 1;
    const TYPE_RESET_PASSWORD = 2;
    const TYPE_CHANGE_EMAIL   = 3;

    use Column\Id;

    use Column\Hash;

    use Column\User;

    use Column\Type;

    use Column\Data;

    use Column\DateCreated;

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->getHash();
    }

}
