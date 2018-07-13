<?php

namespace App\User\Online\Adapter;

/**
 * https://habr.com/post/216047/
 */
class RedisKeyAdapter implements AdapterInterface
{
    protected $key;

    protected $ttl;

    protected $redisClient;

    public function __construct()
    {
        //todo client
        //todo key
        //todo ttl
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

    /**
     * @param $id
     * @return string
     */
    protected function getKey(int $id) : string
    {
        return $this->key . $id;
    }

}
