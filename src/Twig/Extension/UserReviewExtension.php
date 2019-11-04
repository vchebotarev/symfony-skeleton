<?php

namespace App\Twig\Extension;

use App\User\Review\UserReviewType;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UserReviewExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('user_review_type_name', [$this, 'userReviewTypeName']),
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
