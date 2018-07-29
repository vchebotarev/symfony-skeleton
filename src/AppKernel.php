<?php

namespace App;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    /**
     * @inheritdoc
     */
    public function registerBundles()
    {
        $bundles = [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
            new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            new \Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new \Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
            new \EWZ\Bundle\RecaptchaBundle\EWZRecaptchaBundle(),
            new \FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new \Gregwar\CaptchaBundle\GregwarCaptchaBundle(),
            new \Snc\RedisBundle\SncRedisBundle(),
            new \Matthias\SymfonyConsoleForm\Bundle\SymfonyConsoleFormBundle(),
            new \Chebur\LoginFormBundle\CheburLoginFormBundle(),
            new \Chebur\SearchBundle\CheburSearchBundle(),
            new \SmartCore\Bundle\AcceleratorCacheBundle\AcceleratorCacheBundle(),
            new \Http\HttplugBundle\HttplugBundle(),
            new \EmanueleMinotto\TwigCacheBundle\TwigCacheBundle(),
            new \HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
            new \FOS\RestBundle\FOSRestBundle(),
            new \JMS\SerializerBundle\JMSSerializerBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new \Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new \Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
        }

        return $bundles;
    }

    /**
     * @inheritDoc
     */
    protected function getKernelParameters()
    {
        return array_merge(
            parent::getKernelParameters(),
            [
                'kernel.not_debug'  => !$this->debug,
                'kernel.source_dir' => $this->getProjectDir() . '/src',
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getRootDir()
    {
        return __DIR__;
    }

    /**
     * @inheritdoc
     */
    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    /**
     * @inheritdoc
     */
    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    /**
     * @inheritdoc
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getProjectDir().'/config/config_'.$this->getEnvironment().'.yml');
    }

}
