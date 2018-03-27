<?php

namespace App\Entity;

use App\Doctrine\Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserSocialRepository")
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
    protected $socialId = '';

    use Column\Data;

    /**
     * @return string
     */
    public function getSocialId() : string
    {
        return $this->socialId;
    }

    /**
     * @param string $socialId
     * @return $this
     */
    public function setSocialId(string $socialId)
    {
        $this->socialId = $socialId;
        return $this;
    }

}
