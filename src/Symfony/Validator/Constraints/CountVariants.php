<?php

namespace App\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class CountVariants extends Constraint
{
    /**
     * @var string
     */
    public $message = 'This value has invalid count of elements.';

    /**
     * @var array
     */
    public $variants = [];

    /**
     * @inheritDoc
     */
    public function getRequiredOptions()
    {
        return ['variants'];
    }

    /**
     * @inheritDoc
     */
    public function getDefaultOption()
    {
        return 'variants';
    }

}
