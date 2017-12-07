<?php

namespace App\Twig\Extension;

use App\User\Review\UserReviewType;

class UserReviewExtension extends \Twig_Extension
{
    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('user_review_type_name', [$this, 'userReviewTypeName']),
        ];
    }

    /**
     * @param int $type
     * @return string
     */
    public function userReviewTypeName(int $type) : string
    {
        return UserReviewType::getName($type);
    }

}
