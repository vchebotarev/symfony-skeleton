<?php

namespace App\Token;

use FOS\UserBundle\Util\TokenGenerator as FOSTokenGenerator;

class TokenGenerator extends FOSTokenGenerator
{
    public function generateToken()
    {
        return md5(parent::generateToken());
    }

}

