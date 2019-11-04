<?php

namespace App\OAuth\Exception;

use HWI\Bundle\OAuthBundle\Security\Core\Exception\AccountNotLinkedException as BaseAccountNotLinkedException;

class AccountNotLinkedException extends BaseAccountNotLinkedException
{
    public function getMessageKey()
    {
        return 'OAuth account not linked.';
    }

}
