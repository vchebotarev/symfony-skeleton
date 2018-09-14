<?php

namespace App\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Composite;

abstract class Combination extends Composite
{
    /**
     * @var Constraint[]
     */
    public $constraints;

    /**
     * @var bool
     */
    public $breakOnFailure = false;

    /**
     * @inheritDoc
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    protected function initializeNestedConstraints()
    {
        $constraints = $this->getConstraints();
        if (empty($constraints)) {
            return;
        }
        $this->constraints = $constraints;
    }

    /**
     * @return array
     */
    abstract protected function getConstraints();

    /**
     * @inheritDoc
     */
    public function getDefaultOption()
    {
        return 'constraints';
    }

    /**
     * @inheritDoc
     */
    public function getCompositeOption()
    {
        return 'constraints';
    }

    /**
     * @inheritDoc
     */
    public function validatedBy()
    {
        return CombinationValidator::class;
    }

}
