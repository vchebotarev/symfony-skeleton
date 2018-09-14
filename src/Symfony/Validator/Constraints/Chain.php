<?php

namespace App\Symfony\Validator\Constraints;

class Chain extends Combination
{
    /**
     * @var bool
     */
    public $breakOnFailure = true;

    /**
     * @inheritDoc
     */
    protected function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * @inheritDoc
     */
    public function getRequiredOptions()
    {
        return ['constraints'];
    }

}
