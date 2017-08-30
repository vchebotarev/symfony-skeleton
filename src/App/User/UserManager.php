<?php

namespace App\User;

use App\Email\EmailHelper;
use App\FOS\User\AbstractUserManager;
use AppBundle\Entity\User;
use AppBundle\Entity\UserToken;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserManager extends AbstractUserManager
{
    /**
     * @var EmailHelper
     */
    protected $emailHelper;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @param EntityManager         $em
     * @param TokenStorageInterface $tokenStorage
     * @param EmailHelper           $emailHelper
     */
    public function __construct(EntityManager $em, TokenStorageInterface $tokenStorage, EmailHelper $emailHelper)
    {
        $this->objectManager = $em;
        $this->tokenStorage  = $tokenStorage;
        $this->emailHelper   = $emailHelper;
    }

    /**
     * @return User
     */
    public function getCurrentUser()
    {
        if ($this->tokenStorage->getToken() && $this->tokenStorage->getToken()->getUser() instanceof User) {
            return $this->tokenStorage->getToken()->getUser();
        }
        return null;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return User::class;
    }

    /**
     * @return User
     */
    public function createUser()
    {
        /** @var User $user */
        $user = parent::createUser();
        return $user;
    }

    /**
     * @param int|string $idOrUsernameOrEmail
     * @return User|null
     */
    public function findUserByIdOrUsernameOrEmail($idOrUsernameOrEmail)
    {
        if (filter_var($idOrUsernameOrEmail, FILTER_VALIDATE_INT)) {
            return $this->findUserById($idOrUsernameOrEmail);
        }
        return $this->findUserByUsernameOrEmail($idOrUsernameOrEmail);
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function findUserById($id)
    {
        /** @var User $user */
        $user = $this->findUserBy(array(
            'id' => $id,
        ));
        return $user;
    }

    /**
     * @param string $usernameOrEmail
     * @return User|null
     */
    public function findUserByUsernameOrEmail($usernameOrEmail)
    {
        if ($this->emailHelper->isEmail($usernameOrEmail)) {
            return $this->findUserByEmail($usernameOrEmail);
        }
        return $this->findUserByUsername($usernameOrEmail);
    }

    /**
     * @param string $username
     * @return User|null
     */
    public function findUserByUsername($username)
    {
        /** @var User $user */
        $user = $this->findUserBy(array(
            'username' => $username,
        ));
        return $user;
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findUserByEmail($email)
    {
        /** @var User $user */
        $user = $this->findUserBy(array(
            'email' => $email,
        ));
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function findUserByConfirmationToken($token)
    {
        $userToken = $this->objectManager->getRepository(UserToken::class)->findOneBy(array(
            'hash' => $token,
        ));
        if (!$userToken) {
            return null;
        }
        return $userToken->getUser();
    }

}
