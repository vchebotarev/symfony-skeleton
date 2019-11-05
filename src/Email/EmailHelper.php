<?php

namespace App\Email;

class EmailHelper
{
    /**
     * @param string $string
     * @return bool
     */
    public function isEmail($string)
    {
        //1 способ из FOSUser \FOS\UserBundle\Model\UserManager
        if (preg_match('/^.+\@\S+\.\S+$/', $string)) {
            return true;
        }
        return false;

        //2 способ
        //if (filter_var($string, FILTER_VALIDATE_EMAIL)) {
        //    return true;
        //}
        //return false;
    }
}
