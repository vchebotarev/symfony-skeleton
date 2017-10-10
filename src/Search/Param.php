<?php

namespace App\Search;

use Doctrine\Common\Collections\Criteria;

class Param
{
    const FROM = 'from';
    const TO   = 'to';

    const ASC  = Criteria::ASC;
    const DESC = Criteria::DESC;

    const Q       = 'q';
    const CREATED = 'created';
    const UPDATED = 'updated';
    const LOGIN   = 'login';

    const ENABLED = 'enabled';
    const LOCKED  = 'locked';
    const NAME    = 'name';

    const USER     = 'user';
    const USERNAME = 'username';
    const EMAIL    = 'email';

}
