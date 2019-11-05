<?php

namespace App\Auth\Log;

use App\Doctrine\Entity\UserAuthLog;

class AuthLogHelper
{
    /**
     * @var array
     */
    protected $typeNames = [
        UserAuthLog::TYPE_UNKNOWN           => 'Неизвестно',
        UserAuthLog::TYPE_USERNAME_PASSWORD => 'Пароль',
        UserAuthLog::TYPE_REMEMBER_ME       => 'Куки',
        UserAuthLog::TYPE_OAUTH             => 'OAuth',
        UserAuthLog::TYPE_LINK              => 'Ссылка',
    ];

    /**
     * @param int $type
     * @return string
     */
    public function getTypeName(int $type) : string
    {
        if (isset($this->typeNames[$type])) {
            return $this->typeNames[$type];
        }
        return $this->typeNames[UserAuthLog::TYPE_UNKNOWN];
    }

}
