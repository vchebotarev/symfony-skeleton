<?php

namespace App\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonArrayType as BaseJsonArrayType;

class JsonArrayType extends BaseJsonArrayType
{
    /**
     * @inheritDoc
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

}