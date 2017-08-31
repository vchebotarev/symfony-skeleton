<?php

namespace App\User;

use App\Auth\PasswordHelper;
use AppBundle\Entity\User;
use AppBundle\Entity\UserToken;
use FOS\UserBundle\Mailer\MailerInterface;

class UserManipulator
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var MailerInterface
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
     * @param MailerInterface  $mailer
     * @param UserTokenManager $userTokenManager
     */
    public function __construct(UserManager $userManager, PasswordHelper $passwordHelper, MailerInterface $mailer, UserTokenManager $userTokenManager)
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

}
