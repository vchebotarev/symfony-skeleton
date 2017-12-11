<?php

namespace App\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Url extends Constraint
{
    /**
     * @var string
     */
    public $message = 'This value is not a valid URL.';

    /**
     * @var string
     */
    public $messageDns = 'The host could not be resolved.';

    /**
     * @var string
     */
    public $messageCode = 'The url is not reachable';

    /**
     * @var string[]
     */
    public $messages = [
        'scheme'   => '',
        'host'     => '',
        'port'     => '',
        'user'     => '',
        'pass'     => '',
        'path'     => '',
        'query'    => '',
        'fragment' => '',
    ];

    /**
     * @var bool
     */
    public $checkDns = false;

    /**
     * @var bool
     */
    public $checkCode = false;

    /**
     * @var array|string|null
     */
    public $scheme = ['http', 'https'];

    /**
     * По умолчанию только буквенные хосты (google.com, но не 123.1.2.234, или же ipv6)
     * @var array|string|null
     */
    public $host = '([\pL\pN\pS-\.])+(\.?([\pL\pN]|xn\-\-[\pL\pN-]+)+\.?)';

    /**
     * По умолчанию запрет на указание порта
     * @var array|string|null
     */
    public $port = [];

    /**
     * По умолчанию запрет на указание пользователя
     * @var array|string|null
     */
    public $user = [];

    /**
     * По умолчанию запрет на указание пароля
     * @var array|string|null
     */
    public $pass = [];

    /**
     * @var array|string|null
     */
    public $path;

    /**
     * @var array|string|null
     */
    public $query;

    /**
     * @var array|string|null
     */
    public $fragment;

}
