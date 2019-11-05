<?php

namespace App\User;

use App\Token\TokenGenerator;
use App\Doctrine\Entity\User;
use App\Doctrine\Entity\UserToken;
use Doctrine\ORM\EntityManager;

class UserTokenManager
{
    /**
     * @var TokenGenerator
     */
    protected $tokenGenerator;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager  $em
     * @param TokenGenerator $tokenGenerator
     */
    public function __construct(EntityManager $em, TokenGenerator $tokenGenerator)
    {
        $this->em             = $em;
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return [
            UserToken::TYPE_REGISTRATION,
            UserToken::TYPE_RESET_PASSWORD,
            UserToken::TYPE_CHANGE_EMAIL,
        ];
    }

    /**
     * @param int $type
     * @return int
     */
    public function getTtlByType($type)
    {
        $ttl = 60 * 60;
        switch ($type) {
            case UserToken::TYPE_REGISTRATION:
                $ttl = 60 * 60 * 24; //сутки
                break;
            case UserToken::TYPE_RESET_PASSWORD:
                $ttl = 60 * 60; //один час
                break;
            case UserToken::TYPE_CHANGE_EMAIL:
                $ttl = 60 * 60; //один час
                break;
        }
        return $ttl;
    }

    /**
     * @param int   $type
     * @param User  $user
     * @param array $data
     * @return UserToken
     */
    public function saveToken($type, User $user, array $data = [])
    {
        $userToken = new UserToken();
        $userToken->setUser($user);
        $userToken->setType($type);
        $userToken->setData($data);
        $userToken->setHash($this->tokenGenerator->generateToken());

        $this->em->persist($userToken);
        $this->em->flush($userToken);

        return $userToken;
    }

    /**
     * @param UserToken $userToken
     */
    public function removeToken(UserToken $userToken)
    {
        $this->em->remove($userToken);
        $this->em->flush();
    }

    /**
     * @param string $hash
     * @param int    $type
     * @return null|UserToken
     */
    public function findTokenByHashAndType($hash, $type)
    {
        return $this->em->getRepository(UserToken::class)->findOneBy([
            'hash' => $hash,
            'type' => $type,
        ]);
    }

    /**
     * @param User $user
     * @param int  $type
     * @return UserToken|null
     */
    public function findTokenByUserAndType(User $user, $type)
    {
        return $this->em->getRepository(UserToken::class)->findOneBy([
            'user' => $user,
            'type' => $type,
        ]);
    }
}
