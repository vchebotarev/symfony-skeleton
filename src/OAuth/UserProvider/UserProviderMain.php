<?php

namespace App\OAuth\UserProvider;

use App\Entity\UserSocial;
use App\OAuth\Exception\AccountNotLinkedException;
use App\OAuth\ResourceOwnerHelper;
use Doctrine\ORM\EntityManager;
use HWI\Bundle\OAuthBundle\OAuth\ResourceOwnerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;

class UserProviderMain implements OAuthAwareUserProviderInterface
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
     * @param EntityManager       $em
     * @param ResourceOwnerHelper $resourceOwnerHelper
     */
    public function __construct(EntityManager $em, ResourceOwnerHelper $resourceOwnerHelper)
    {
        $this->em                  = $em;
        $this->resourceOwnerHelper = $resourceOwnerHelper;
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        /** @var ResourceOwnerInterface $resourceOwner */
        $resourceOwner = $response->getResourceOwner();
        /** @var UserSocial $userSocial */
        $userSocial = $this->em->getRepository(UserSocial::class)->findOneBy([
            'type'     => $this->resourceOwnerHelper->getDbId($resourceOwner),
            'socialId' => $response->getUsername(),
        ]);
        //todo 1 запрос к бд
        if (!$userSocial) {
            $exception = new AccountNotLinkedException();
            $exception->setResourceOwnerName($resourceOwner->getName());
            throw $exception;
        }

        return $userSocial->getUser();
    }

}
