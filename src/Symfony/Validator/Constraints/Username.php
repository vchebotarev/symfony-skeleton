<?php

namespace App\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class Username extends Combination
{
    protected function getConstraints()
    {
        $constraints = [
            new Chain([
                //todo translations
                new Length([
                    'minMessage' => 'Логин должен содержать не менее 3 символов',
                    'maxMessage' => 'Логин должен содержать не более 32 символов',
                    'min'        => 3,
                    'max'        => 32,
                ]),
                new Regex([
                    'pattern' => '/[0-9a-z.-_]+/i',
                    'message' => 'Логин может содержать латинские буквы, цифры, знак подчеркивания ("_"), точку ("."), минус ("-").',
                ]),
                new Regex([
                    'pattern' => '/[a-z]/i',
                    'message' => 'В логине должна присутствовать хотя бы одна латинская буква (a-z).',
                ]),
                new Regex([
                    'pattern' => '/^[0-9a-z]/i',
                    'message' => 'Логин должен начинаться с латинской буквы (a-z) или цифры (0-9).',
                ]),
                new Regex([
                    'pattern' => '/[0-9a-z]$/i',
                    'message' => 'Логин должен заканчиваться на латинскую букву (a-z) или цифру (0-9).',
                ]),
                new Regex([
                    'pattern' => '/admin|support|moderator/i',
                    'match'   => false,
                    'message' => 'Логин зарезервирован системой',
                ]),
            ]),
        ];
        return $constraints;
    }
}
