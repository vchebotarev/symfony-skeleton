<?php

namespace App\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Md5 extends Constraint
{
    /**
     * @var string
     */
    public $message = 'This is not a valid md5 hash';

}
