<?php

namespace App\Twig\Extension;

use App\Auth\Log\AuthLogHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AuthLogExtension extends AbstractExtension
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
            new TwigFunction('auth_log_type_name', [$this, 'getName']),
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
