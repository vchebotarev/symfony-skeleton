<?php

namespace App\Twig\Extension;

use App\OAuth\ResourceOwnerHelper;
use HWI\Bundle\OAuthBundle\OAuth\ResourceOwnerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class OAuthExtension extends AbstractExtension
{
    /**
     * @var ResourceOwnerHelper
     */
    private $resourceOwnerHelper;

    /**
     * @param ResourceOwnerHelper $resourceOwnerHelper
     */
    public function __construct(ResourceOwnerHelper $resourceOwnerHelper)
    {
        $this->resourceOwnerHelper = $resourceOwnerHelper;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('oauth_name',       [$this, 'getResourceOwnerPublicName']),
            new TwigFunction('oauth_id',         [$this, 'getResourceOwnerDbId']),
            new TwigFunction('oauth_icon_class', [$this, 'getResourceOwnerIconClass']),
        ];
    }

    /**
     * @param ResourceOwnerInterface $resourceOwner
     * @return string
     */
    public function getResourceOwnerPublicName(ResourceOwnerInterface $resourceOwner) : string
    {
        return $this->resourceOwnerHelper->getNamePublic($resourceOwner);
    }

    /**
     * @param ResourceOwnerInterface $resourceOwner
     * @return int
     */
    public function getResourceOwnerDbId(ResourceOwnerInterface $resourceOwner) : int
    {
        return $this->resourceOwnerHelper->getDbId($resourceOwner);
    }

    /**
     * @param ResourceOwnerInterface $resourceOwner
     * @return string
     */
    public function getResourceOwnerIconClass(ResourceOwnerInterface $resourceOwner) : string
    {
        return $this->resourceOwnerHelper->getIconClass($resourceOwner);
    }

}
