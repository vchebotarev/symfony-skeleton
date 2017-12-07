<?php

namespace App\User\Review;

use App\Entity\User;
use App\Entity\UserReview;
use App\User\UserManager;
use Doctrine\ORM\EntityManager;

class UserReviewManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @param EntityManager $em
     * @param UserManager   $userManager
     */
    public function __construct(EntityManager $em, UserManager $userManager)
    {
        $this->em          = $em;
        $this->userManager = $userManager;
    }

    /**
     * @param User   $user
     * @param int    $type
     * @param string $body
     * @return UserReview
     */
    public function create(User $user, int $type, string $body) : UserReview
    {
        $review = new UserReview();
        $review->setUserCreated($this->userManager->getCurrentUser());
        $review->setUser($user);
        $review->setType($type);
        $review->setBody($body);

        $this->em->persist($review);
        $this->em->flush($review);

        return $review;
    }

    /**
     * @param UserReview $userReview
     */
    public function delete(UserReview $userReview)
    {
        $this->em->remove($userReview);
        $this->em->flush($userReview);
    }

}
