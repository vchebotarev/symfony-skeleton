<?php

namespace App\Entity;

use App\Doctrine\Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_review")
 * @ORM\Entity(repositoryClass="App\Repository\UserReviewRepository")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_created_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $userCreated;

    use Column\Type;

    use Column\Body;

    use Column\DateCreated;

    /**
     * @return User
     */
    public function getUserCreated()
    {
        return $this->userCreated;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUserCreated(User $user)
    {
        $this->userCreated = $user;
        return $this;
    }

}
