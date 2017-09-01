<?php

namespace App\User\Security;

use App\User\UserTokenManager;
use AppBundle\Entity\User;
use AppBundle\Entity\UserToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    const CHANGE_EMAIL = 'user.change.email';

    /**
     * @var UserTokenManager
     */
    protected $userTokenManager;

    /**
     * @param UserTokenManager $userTokenManager
     */
    public function __construct(UserTokenManager $userTokenManager)
    {
        $this->userTokenManager = $userTokenManager;
    }

    /**
     * @inheritDoc
     */
    protected function supports($attribute, $subject)
    {
        if ($attribute == self::CHANGE_EMAIL) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $currentUser */
        $currentUser = $token->getUser();

        if ($attribute == self::CHANGE_EMAIL) {
            if (!$currentUser) {
                return false;
            }
            $userToken = $this->userTokenManager->findTokenByUserAndType($currentUser, UserToken::TYPE_CHANGE_EMAIL);
            if ($userToken) {
                return false;
            }
            return true;
        }

        throw new \RuntimeException();
    }

}
