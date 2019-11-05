<?php

namespace App\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Types\DateTimeTzType as BaseDateTimeTzType;

/**
 * Везде пишут, что надо использовать @ORM\Version для datetime
 * в таком случае добавляется дефолтное значение CURRENT_TIMESTAMP
 * если же таких полей больше одного, то фатал, что полей с CURRENT_TIMESTAMP не должность больше одного
 * а это обязывает нас заморачиваться с этими значениями по умолчанию
 */
class DateTimeTzType extends BaseDateTimeTzType
{
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        if ($platform instanceof MySqlPlatform) {
            return 'TIMESTAMP';
        }
        return parent::getSQLDeclaration($fieldDeclaration, $platform);
    }
}
