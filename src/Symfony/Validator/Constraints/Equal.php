<?php

namespace App\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Equal extends Constraint
{
    /**
     * @var string
     */
    public $message = 'This value does not equal the {{ field }} field';

    /**
     * @var string
     */
    public $field;

    /**
     * @var bool
     */
    public $notEqualMode = false;

    public function getDefaultOption()
    {
        return 'field';
    }

    public function getRequiredOptions()
    {
        return ['field'];
    }

}
