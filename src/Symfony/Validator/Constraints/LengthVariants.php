<?php

namespace App\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class LengthVariants extends Constraint
{
    /**
     * @var string
     */
    public $message = 'This value has invalid count of characters.';

    /**
     * @var string
     */
    public $charsetMessage = 'This value does not match the expected {{ charset }} charset.';

    /**
     * @var array
     */
    public $variants = [];

    /**
     * @var string
     */
    public $charset = 'UTF-8';

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
