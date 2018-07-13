<?php

namespace App\User\Online;

use App\Entity\User;
use App\User\Online\Adapter\AdapterInterface;

/**
 * https://stackoverflow.com/questions/21878540/tracking-online-users-using-redis
 */
class UserOnlineManager
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isOnline(User $user) : bool
    {
        $id = $this->getIdentity($user);

        return $this->adapter->isOnline($id);
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setOnline(User $user)
    {
        $id = $this->getIdentity($user);

        $this->adapter->setOnline($id);

        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function unsetOnline(User $user)
    {
        $id = $this->getIdentity($user);

        $this->adapter->unsetOnline($id);

        return $this;
    }

    /**
     * @return int
     */
    public function countOnline() : int
    {
        return $this->adapter->countOnline();
    }

    /**
     * @return User[]
     */
    public function getOnline()
    {
        $userIds = $this->adapter->getOnline();

        //todo return User[]
    }

    /**
     * @param User $user
     * @return int
     */
    protected function getIdentity(User $user) : int
    {
        return $user->getId();
    }

}
