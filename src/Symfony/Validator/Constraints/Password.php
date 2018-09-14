<?php

namespace App\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Length;

class Password extends Combination
{
    /**
     * @inheritDoc
     */
    protected function getConstraints()
    {
        $constraints = [
            new Length([
                //todo translations
                'minMessage' => 'Пароль должен содержать не менее 6 символов',
                'maxMessage' => 'Пароль должен содержать не более 128 символов',
                'min'        => 6,
                'max'        => 128,
            ]),
        ];
        return $constraints;
    }

}
