<?php

namespace App\User;

interface GenderedInterface
{
    const GENDER_UNKNOWN = 0;
    const GENDER_MALE    = 1;
    const GENDER_FEMALE  = 2;

    /**
     * @return int
     */
    public function getGender() : int;

}
