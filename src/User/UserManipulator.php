<?php

namespace App\User;

use App\Auth\PasswordHelper;
use App\Mailer\MailerTokened;
use App\Entity\User;
use App\Entity\UserToken;

class UserManipulator
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var MailerTokened
     */
    protected $mailer;

    /**
     * @var PasswordHelper
     */
    protected $passwordHelper;

    /**
     * @var UserTokenManager
     */
    protected $userTokenManager;

    /**
     * @param UserManager      $userManager
     * @param PasswordHelper   $passwordHelper
     * @param MailerTokened    $mailer
     * @param UserTokenManager $userTokenManager
     */
    public function __construct(UserManager $userManager, PasswordHelper $passwordHelper, MailerTokened $mailer, UserTokenManager $userTokenManager)
    {
        $this->userManager      = $userManager;
        $this->mailer           = $mailer;
        $this->passwordHelper   = $passwordHelper;
        $this->userTokenManager = $userTokenManager;
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @return User
     */
    public function create(string $username, string $email, string $password): User
    {
        $user = $this->userManager->createUser();

        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($this->passwordHelper->encodePassword($password, $user));

        $this->userManager->updateUser($user);

        $this->mailer->sendConfirmationEmailMessage($user);

        return $user;
    }

    /**
     * @param User $user
     */
    public function enable(User $user)
    {
        if ($user->isEnabled()) {
            return;
        }

        $user->setIsEnabled(true);
        $this->userManager->updateUser($user);

        $userToken = $this->userTokenManager->findTokenByUserAndType($user, UserToken::TYPE_REGISTRATION);
        if ($userToken) {
            $this->userTokenManager->removeToken($userToken);
        }
    }

    /**
     * @param User   $user
     * @param string $password
     */
    public function changePassword(User $user, string $password)
    {
        $user->setPassword($this->passwordHelper->encodePassword($password, $user));
        $this->userManager->updateUser($user);
    }

    /**
     * @param User   $user
     * @param string $email
     */
    public function changeEmail(User $user, string $email)
    {
        $user->setEmail($email);
        $this->userManager->updateUser($user);
    }

    /**
     * @param User   $user
     * @param string $username
     */
    public function changeUsername(User $user, string $username)
    {
        $user->setUsername($username);
        $this->userManager->updateUser($user);
    }

    /**
     * @param User $user
     */
    public function lock(User $user)
    {
        $user->setIsLocked(true);
        $this->userManager->updateUser($user);
    }

    /**
     * @param User $user
     */
    public function unlock(User $user)
    {
        $user->setIsLocked(false);
        $this->userManager->updateUser($user);
    }

    /**
     * @param User   $user
     * @param string $role
     */
    public function roleAdd(User $user, string $role)
    {
        $user->addRole($role);
        $this->userManager->updateUser($user);
    }

    /**
     * @param User   $user
     * @param string $role
     */
    public function roleRemove(User $user, string $role)
    {
        $user->removeRole($role);
        $this->userManager->updateUser($user);
    }

}
