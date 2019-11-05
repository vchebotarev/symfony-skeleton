<?php

namespace App\Doctrine\Entity;

use App\Doctrine\Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_review")
 * @ORM\Entity(repositoryClass="App\Doctrine\Repository\UserReviewRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class UserReview
{
    const TYPE_NEUTRAL  = 0;
    const TYPE_POSITIVE = 1;
    const TYPE_NEGATIVE = -1;

    use Column\Id;

    use Column\User;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Doctrine\Entity\User")
     * @ORM\JoinColumn(name="user_created_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $userCreated;

    use Column\Type;

    use Column\Body;

    use Column\DateCreated;

    public function getUserCreated() : User
    {
        return $this->userCreated;
    }

    public function setUserCreated(User $user)
    {
        $this->userCreated = $user;
        return $this;
    }

}
