<?php

namespace App\Doctrine\Cache\Provider\Definition;

use Doctrine\Bundle\DoctrineCacheBundle\DependencyInjection\Definition\CacheDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class Psr6Definition extends CacheDefinition
{
    /**
     * @inheritDoc
     */
    public function configure($name, array $config, Definition $service, ContainerBuilder $container)
    {
        $service->addArgument(new Reference($config['custom_provider']['options']['cache_pool']));
    }
}
