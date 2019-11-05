<?php

namespace App\OAuth;

use App\Doctrine\Entity\User;
use App\Doctrine\Entity\UserSocial;
use Doctrine\ORM\EntityManager;
use HWI\Bundle\OAuthBundle\Connect\AccountConnectorInterface;
use HWI\Bundle\OAuthBundle\OAuth\ResourceOwnerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class Connector implements AccountConnectorInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ResourceOwnerHelper
     */
    protected $resourceOwnerHelper;

    /**
     * @param EntityManager       $entityManager
     * @param ResourceOwnerHelper $resourceOwnerHelper
     */
    public function __construct(EntityManager $entityManager, ResourceOwnerHelper $resourceOwnerHelper)
    {
        $this->em                  = $entityManager;
        $this->resourceOwnerHelper = $resourceOwnerHelper;
    }

    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        /** @var User $user */

        $resourceOwner = $response->getResourceOwner();

        $userSocial = $this->em->getRepository(UserSocial::class)->findOneBy([
            'user' => $user,
            'type' => $this->resourceOwnerHelper->getDbId($resourceOwner),
        ]);
        if ($userSocial) { //К пользователю уже прикреплена учетка из социалки
            return;
        }

        $userSocial = $userSocial = $this->em->getRepository(UserSocial::class)->findOneBy([
            'socialId' => $response->getUsername(),
            'type'     => $this->resourceOwnerHelper->getDbId($resourceOwner),
        ]);
        if (!$userSocial) { //Если закреплена за другим пользователем, то перепривязываем на текущего
            $userSocial = new UserSocial();
            $userSocial->setType($this->resourceOwnerHelper->getDbId($resourceOwner));
            $userSocial->setSocialId($response->getUsername());
        }

        $userSocial->setData([ //вдруг потом пригодится
            'username'  => $response->getUsername(),
            'email'     => $response->getEmail(),
            'firstname' => $response->getFirstName(),
            'lastname'  => $response->getLastName(),
            'nickname'  => $response->getNickname(),
            'realname'  => $response->getRealName(),
            'avatar'    => $response->getProfilePicture(),
        ]);
        $userSocial->setUser($user);

        $this->em->persist($userSocial);
        $this->em->flush();
    }

    /**
     * @param UserInterface          $user
     * @param ResourceOwnerInterface $resourceOwner
     */
    public function disconnect(UserInterface $user, ResourceOwnerInterface $resourceOwner)
    {
        /** @var UserSocial $userSocial */
        $userSocial = $this->em->getRepository(UserSocial::class)->findOneBy([
            'user' => $user,
            'type' => $this->resourceOwnerHelper->getDbId($resourceOwner),
        ]);
        if (!$userSocial) {
            //todo exception
            return;
        }

        $this->em->remove($userSocial);
        $this->em->flush();
    }

}
