<?php

namespace App\Doctrine\Cache\Provider;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Psr16Cache as Psr6ToPsr16;

class Psr6Cache extends Psr16Cache
{
    /**
     * @param CacheItemPoolInterface $pool
     */
    public function __construct(CacheItemPoolInterface $pool)
    {
        $psr16Cache = new Psr6ToPsr16($pool);
        parent::__construct($psr16Cache);
    }
}
