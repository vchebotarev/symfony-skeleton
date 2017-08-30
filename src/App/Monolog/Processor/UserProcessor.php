<?php

namespace App\Monolog\Processor;

use App\User\UserManager;

/**
 * @todo под очень большим вопросом
 */
class UserProcessor
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        $user = $this->userManager->getCurrentUser();
        $record['user'] = $user ? $user : 'anonymous';
        return $record;
    }

}
