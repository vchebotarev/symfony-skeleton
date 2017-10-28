<?php

namespace App\User\Security;

use App\User\UserTokenManager;
use App\Entity\User;
use App\Entity\UserToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    const CHANGE_EMAIL  = 'user.change.email';
    const VIEW_AUTH_LOG = 'user.view.auth_log';
    const LIST          = 'user.list';
    const VIEW          = 'user.view';
    const CREATE        = 'user.create';
    const EDIT          = 'user.edit';
    const ENABLE        = 'user.enable';
    const LOCK          = 'user.lock';
    const UNLOCK        = 'user.unlock';

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
    protected function supports($attribute, $user)
    {
        if ($attribute == self::CHANGE_EMAIL) {
            return true;
        }
        if ($attribute == self::VIEW_AUTH_LOG) {
            return true;
        }
        if ($attribute == self::LIST) {
            return true;
        }
        if ($attribute == self::CREATE) {
            return true;
        }
        if ($user instanceof User && $attribute == self::VIEW) {
            return true;
        }
        if ($user instanceof User && $attribute == self::EDIT) {
            return true;
        }
        if ($user instanceof User && $attribute == self::ENABLE) {
            return true;
        }
        if ($user instanceof User && $attribute == self::LOCK) {
            return true;
        }
        if ($user instanceof User && $attribute == self::UNLOCK) {
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
        if ($attribute == self::LIST) {
            return $this->decisionManager->decide($token, [User::ROLE_ADMIN]);
        }
        if ($attribute == self::CREATE) {
            return $this->decisionManager->decide($token, [User::ROLE_ADMIN]);
        }
        if ($attribute == self::VIEW) {
            return $this->decisionManager->decide($token, [User::ROLE_ADMIN]);
        }
        if ($attribute == self::EDIT) {
            return $this->decisionManager->decide($token, [User::ROLE_ADMIN]);
        }
        if ($attribute == self::ENABLE) {
            if ($user->isEnabled()) {
                return false;
            }
            return $this->decisionManager->decide($token, [User::ROLE_ADMIN]);
        }
        if ($attribute == self::LOCK) {
            if ($user->isLocked()) {
                return false;
            }
            return $this->decisionManager->decide($token, [User::ROLE_ADMIN]);
        }
        if ($attribute == self::UNLOCK) {
            if (!$user->isLocked()) {
                return false;
            }
            return $this->decisionManager->decide($token, [User::ROLE_ADMIN]);
        }

        throw new \RuntimeException();
    }

}
