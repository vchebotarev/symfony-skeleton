<?php

namespace App\User\Security;

use App\Doctrine\Entity\User;
use App\Doctrine\Entity\UserToken;
use App\User\UserTokenManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    const CHANGE_EMAIL      = 'user.change.email';
    const VIEW_AUTH_LOG     = 'user.view.auth_log';
    const LIST              = 'user.list';
    const VIEW              = 'user.view';
    const CREATE            = 'user.create';
    const EDIT              = 'user.edit';
    const ENABLE            = 'user.enable';
    const LOCK              = 'user.lock';
    const UNLOCK            = 'user.unlock';
    const ROLE_ADD_ADMIN    = 'user.role.add.admin';
    const ROLE_REMOVE_ADMIN = 'user.role.remove.admin';

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
        if ($user instanceof User && $attribute == self::ROLE_ADD_ADMIN) {
            return true;
        }
        if ($user instanceof User && $attribute == self::ROLE_REMOVE_ADMIN) {
            return true;
        }

        return false;
    }

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
            if ($this->decisionManager->decide($token, [User::ROLE_ADMIN])) {
                return true;
            }
            return !$user->isLocked() && $user->isEnabled();
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
        if ($attribute == self::ROLE_ADD_ADMIN) {
            if ($user->isAdmin()) {
                return false;
            }
            return $this->decisionManager->decide($token, [User::ROLE_SUPER_ADMIN]);
        }
        if ($attribute == self::ROLE_REMOVE_ADMIN) {
            if (!$user->isAdmin()) {
                return false;
            }
            return $this->decisionManager->decide($token, [User::ROLE_SUPER_ADMIN]);
        }

        throw new \RuntimeException();
    }

}
