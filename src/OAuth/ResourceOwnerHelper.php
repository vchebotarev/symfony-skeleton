<?php

namespace App\OAuth;

use HWI\Bundle\OAuthBundle\OAuth\ResourceOwnerInterface;

class ResourceOwnerHelper
{
    const FACEBOOK_NAME      = 'facebook';
    const GOOGLE_NAME        = 'google';
    const INSTAGRAM_NAME     = 'instagram';
    const LINKEDIN_NAME      = 'linkedin';
    const MAILRU_NAME        = 'mailru';
    const ODNOKLASSNIKI_NAME = 'odnoklassniki';
    const TWITTER_NAME       = 'twitter';
    const VKONTAKTE_NAME     = 'vkontakte';
    const WINDOWS_NAME       = 'windows';
    const YAHOO_NAME         = 'yahoo';
    const YANDEX_NAME        = 'yandex';
    const YOUTUBE_NAME       = 'youtube';

    /**
     * @var array
     */
    protected $arr = [
        self::FACEBOOK_NAME      => [1,  'Facebook',      'fa fa-facebook-f',   ],
        self::GOOGLE_NAME        => [2,  'Google',        'fa fa-google',       ],
        self::INSTAGRAM_NAME     => [3,  'Instagram',     'fa fa-instagram',    ],
        self::LINKEDIN_NAME      => [4,  'LinkedIn',      'fa fa-linkedin',     ],
        self::MAILRU_NAME        => [5,  'Mail.Ru',       'fa fa-at',           ],
        self::ODNOKLASSNIKI_NAME => [6,  'Одноклассники', 'fa fa-odnoklassniki',],
        self::TWITTER_NAME       => [7,  'Twitter',       'fa fa-twitter',      ],
        self::VKONTAKTE_NAME     => [8,  'ВКонтакте',     'fa fa-vk',           ],
        self::WINDOWS_NAME       => [9,  'Windows Live',  'fa fa-windows',      ],
        self::YAHOO_NAME         => [10, 'Yahoo',         'fa fa-yahoo',        ],
        self::YANDEX_NAME        => [11, 'Яндекс',        'fa fa-yandex',       ],
        self::YOUTUBE_NAME       => [12, 'Youtube',       'fa fa-youtube-play', ],
    ];

    /**
     * Id социалки для хранения в базе
     * @param ResourceOwnerInterface $resourceOwner
     * @return int
     */
    public function getDbId(ResourceOwnerInterface $resourceOwner) : int
    {
        $name = $resourceOwner->getName();
        if (!isset($this->arr[$name])) {
            throw $this->createInvalidException($resourceOwner);
        }
        return $this->arr[$name][0];
    }

    /**
     * Публичное имя социалки
     * @param ResourceOwnerInterface $resourceOwner
     * @return string
     */
    public function getNamePublic(ResourceOwnerInterface $resourceOwner) : string
    {
        $name = $resourceOwner->getName();
        if (!isset($this->arr[$name])) {
            throw $this->createInvalidException($resourceOwner);
        }
        return $this->arr[$name][1];
    }

    /**
     * @param ResourceOwnerInterface $resourceOwner
     * @return string
     */
    public function getIconClass(ResourceOwnerInterface $resourceOwner) : string
    {
        $name = $resourceOwner->getName();
        if (!isset($this->arr[$name])) {
            throw $this->createInvalidException($resourceOwner);
        }
        return $this->arr[$name][2];
    }

    /**
     * @param ResourceOwnerInterface $resourceOwner
     * @return \InvalidArgumentException
     */
    protected function createInvalidException(ResourceOwnerInterface $resourceOwner) : \InvalidArgumentException
    {
        return new \InvalidArgumentException('Resource owner with name "'.$resourceOwner->getName().'" is not configured');
    }
}
