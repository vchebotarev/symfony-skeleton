<?php

namespace App\User\Security;

use App\User\UserTokenManager;
use AppBundle\Entity\User;
use AppBundle\Entity\UserToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    const CHANGE_EMAIL  = 'user.change.email';
    const VIEW_AUTH_LOG = 'user.view.auth_log';

    /**
     * @var UserTokenManager
     */
    protected $userTokenManager;

    /**
     * @var AccessDecisionManagerInterface
     */
    protected $decisionManager;

    /**
     * @param AccessDecisionManagerInterface $decisionManager
     * @param UserTokenManager               $userTokenManager
     */
    public function __construct(AccessDecisionManagerInterface $decisionManager, UserTokenManager $userTokenManager)
    {
        $this->decisionManager  = $decisionManager;
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
        if ($attribute == self::VIEW_AUTH_LOG) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute($attribute, $user, TokenInterface $token)
    {
        /** @var User $user */
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
        if ($attribute == self::VIEW_AUTH_LOG) {
            if ($this->decisionManager->decide($token, [User::ROLE_ADMIN])) {
                return true;
            }
            if (!$user) {
                return false;
            }
            return $user->getId() == $currentUser->getId();
        }

        throw new \RuntimeException();
    }

}
