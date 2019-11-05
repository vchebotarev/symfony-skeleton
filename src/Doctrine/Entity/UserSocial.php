<?php

namespace App\Doctrine\Entity;

use App\Doctrine\Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Doctrine\Repository\UserSocialRepository")
 * @ORM\Table(name="user_social")
 */
class UserSocial
{
    use Column\Id;

    use Column\User;

    use Column\Type;

    /**
     * @var string
     * @ORM\Column(name="social_id", type="string", nullable=false, options={"default": ""})
     */
    private $socialId = '';

    use Column\Data;

    public function getSocialId() : string
    {
        return $this->socialId;
    }

    public function setSocialId(string $socialId)
    {
        $this->socialId = $socialId;
        return $this;
    }

}
