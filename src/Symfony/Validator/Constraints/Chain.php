<?php

namespace App\Symfony\Validator\Constraints;

class Chain extends Combination
{
    /**
     * @var bool
     */
    public $breakOnFailure = true;

    protected function getConstraints()
    {
        return $this->constraints;
    }

    public function getRequiredOptions()
    {
        return ['constraints'];
    }

}
