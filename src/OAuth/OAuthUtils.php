<?php

namespace App\OAuth;

use HWI\Bundle\OAuthBundle\OAuth\ResourceOwnerInterface;
use HWI\Bundle\OAuthBundle\Security\OAuthUtils as BaseOAuthUtils;

class OAuthUtils extends BaseOAuthUtils
{
    /**
     * @return ResourceOwnerInterface[]
     */
    public function getResourceOwnersObjects()
    {
        $resourceOwners = [];
        foreach ($this->ownerMaps as $ownerMap) {
            foreach ($ownerMap->getResourceOwners() as $name => $checkUrl) {
                $resourceOwners[$name] = $ownerMap->getResourceOwnerByName($name);
            }
        }
        return $resourceOwners;
    }

    /**
     * @param string $name
     * @return ResourceOwnerInterface|null
     */
    public function getResourceOwnerObjectByName($name)
    {
        foreach ($this->ownerMaps as $ownerMap) {
            $resourceOwner = $ownerMap->getResourceOwnerByName($name);
            if ($resourceOwner) {
                return $resourceOwner;
            }
        }
        return null;
    }
}
