<?php

namespace App\Symfony\Validator\Constraints;

class Chain extends Combination
{
    /**
     * @var bool
     */
    public $breakOnFailure = true;

    /**
     * @inheritdoc
     */
    protected function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * @inheritdoc
     */
    public function getRequiredOptions()
    {
        return ['constraints'];
    }

}
