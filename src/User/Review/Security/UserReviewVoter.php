<?php

namespace App\User\Review\Security;

use App\Entity\User;
use App\Entity\UserReview;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserReviewVoter extends Voter
{
    const CREATE = 'user.review.create';
    const DELETE = 'user.review.delete';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var AccessDecisionManagerInterface
     */
    protected $decisionManager;

    /**
     * @param AccessDecisionManagerInterface $decisionManager
     * @param EntityManager                  $em
     */
    public function __construct(AccessDecisionManagerInterface $decisionManager, EntityManager $em)
    {
        $this->decisionManager = $decisionManager;
        $this->em              = $em;
    }

    protected function supports($attribute, $subject)
    {
        if ($attribute == self::CREATE && $subject instanceof User) {
            return true;
        }
        if ($attribute == self::DELETE && $subject instanceof UserReview) {
            return true;
        }
        return false;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $currentUser */
        $currentUser = $token->getUser();

        if ($attribute == self::CREATE && $subject instanceof User) {
            if ($subject->isLocked()) {
                return false;
            }
            if (!$subject->isEnabled()) {
                return false;
            }
            if ($subject->isAdmin()) { //todo use decision manager
                return false;
            }
            if ($this->decisionManager->decide($token, [User::ROLE_ADMIN])) {
                return false;
            }
            if ($this->em->getRepository(UserReview::class)->hasReviewFromUser($subject, $currentUser)) {
                return false;
            }
            return true;
        }
        if ($attribute == self::DELETE && $subject instanceof UserReview) {
            if ($this->decisionManager->decide($token, [User::ROLE_ADMIN])) {
                return true;
            }
            if ($subject->getUserCreated()->getId() == $currentUser->getId()) {
                return true;
            }
            return true;
        }

        throw new \RuntimeException();
    }

}
