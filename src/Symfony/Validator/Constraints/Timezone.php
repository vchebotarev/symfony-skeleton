<?php

namespace App\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Timezone extends Constraint
{
    /**
     * @var int
     */
    public $what = \DateTimeZone::ALL;

    /**
     * @var
     */
    public $country;

    /**
     * @var string
     */
    public $message = 'Invalid timezone';

}
