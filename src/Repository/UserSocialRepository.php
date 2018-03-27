<?php

namespace App\Repository;

use App\Doctrine\ORM\EntityRepository;
use App\Entity\User;
use App\Entity\UserSocial;

class UserSocialRepository extends EntityRepository
{
    /**
     * Получить все прикрепленные соц. учетки. ключ = тип социалки
     * @param User $user
     * @return array
     */
    public function findByUser(User $user)
    {
        $arr = [];
        /** @var UserSocial[] $rows */
        $rows = $this->findBy([
            'user' => $user,
        ]);
        foreach ($rows as $row) {
            $arr[$row->getType()] = $row;
        }
        return $arr;
    }

}
