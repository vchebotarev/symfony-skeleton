<?php

namespace App\Twig\Extension;

use App\Auth\Log\AuthLogHelper;

class AuthLogExtension extends \Twig_Extension
{
    /**
     * @var AuthLogHelper
     */
    protected $authLogHelper;

    /**
     * @param AuthLogHelper $authLogHelper
     */
    public function __construct(AuthLogHelper $authLogHelper)
    {
        $this->authLogHelper = $authLogHelper;
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('auth_log_type_name', [$this, 'getName']),
        ];
    }

    /**
     * @param int $type
     * @return string
     */
    public function getName(int $type) : string
    {
        return $this->authLogHelper->getTypeName($type);
    }

}
