<?php

namespace App\User\Online\Adapter;

class RedisSetAdapter implements AdapterInterface
{
    protected $key;

    protected $redisClient;

    public function __construct()
    {
        //todo client
        //todo key
    }

    /**
     * @inheritDoc
     */
    public function isOnline(int $id) : bool
    {
        // TODO: Implement isOnline() method.
    }

    /**
     * @inheritDoc
     */
    public function setOnline(int $id)
    {
        // TODO: Implement setOnline() method.
    }

    /**
     * @inheritDoc
     */
    public function unsetOnline(int $id)
    {
        // TODO: Implement unsetOnline() method.
    }

    /**
     * @inheritDoc
     */
    public function countOnline() : int
    {
        // TODO: Implement countOnline() method.
    }

    /**
     * @inheritDoc
     */
    public function getOnline() : iterable
    {
        // TODO: Implement getOnline() method.
    }

}
