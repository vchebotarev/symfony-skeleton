<?php

namespace App\Doctrine\Cache\Provider;

use Doctrine\Common\Cache\CacheProvider;
use Psr\SimpleCache\CacheInterface;

class Psr16Cache extends CacheProvider
{
    /**
     * @var CacheInterface
     */
    protected $psr16Cache;

    /**
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->psr16Cache = $cache;
    }

    /**
     * @inheritDoc
     */
    protected function doFetch($id)
    {
        $id = $this->normalizeKey($id);
        return $this->psr16Cache->get($id);
    }

    /**
     * @inheritDoc
     */
    protected function doContains($id)
    {
        $id = $this->normalizeKey($id);
        return $this->psr16Cache->has($id);
    }

    /**
     * @inheritDoc
     */
    protected function doSave($id, $data, $lifeTime = 0)
    {
        $id = $this->normalizeKey($id);
        return $this->psr16Cache->set($id, $data, $lifeTime);
    }

    /**
     * @inheritDoc
     */
    protected function doDelete($id)
    {
        $id = $this->normalizeKey($id);
        return $this->psr16Cache->delete($id);
    }

    /**
     * @inheritDoc
     */
    protected function doFlush()
    {
        return $this->deleteAll();
    }

    /**
     * @inheritDoc
     */
    protected function doGetStats()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    protected function doFetchMultiple(array $keys)
    {
        $keys = $this->normalizeKey($keys);
        return $this->psr16Cache->getMultiple($keys);
    }

    /**
     * @inheritDoc
     */
    protected function doSaveMultiple(array $keysAndValues, $lifetime = 0)
    {
        $normalizedKeysAndValues = [];
        foreach ($keysAndValues as $key => $value) {
            $normalizedKeysAndValues[$this->normalizeKey($key)] = $value;
        }
        return $this->psr16Cache->setMultiple($normalizedKeysAndValues, $lifetime);
    }

    /**
     * @inheritDoc
     */
    protected function doDeleteMultiple(array $keys)
    {
        $keys = $this->normalizeKey($keys);
        return $this->psr16Cache->deleteMultiple($keys);
    }

    /**
     * @param string|string[] $key
     * @return null|string|string[]|iterable
     */
    protected function normalizeKey($key)
    {
        $f = function($key) {
            if (preg_match('|[\{\}\(\)/\\\@\:]|', $key)) { //from cache/cache-bundle
                return preg_replace('|[\{\}\(\)/\\\@\:]|', '_', $key);
            }
            return $key;
        };
        if (is_iterable($key)) {
            array_map($f, $key);
        }
        return $f($key);
    }
}
