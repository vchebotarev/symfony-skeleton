<?php

namespace App\User\Review;

use App\Entity\UserReview;

class UserReviewType
{
    /**
     * @var array
     */
    protected static $types = [
        UserReview::TYPE_NEGATIVE => 'Отрицательный',
        UserReview::TYPE_NEUTRAL  => 'Нейтральный',
        UserReview::TYPE_POSITIVE => 'Положительный',
    ];

    /**
     * @param int $type
     * @return string
     */
    public static function getName(int $type) : string
    {
        return isset(self::$types[$type]) ? self::$types[$type] : '';
    }

    /**
     * @return array
     */
    public static function getNames() : array
    {
        return self::$types;
    }

}
