<?php

namespace App;

use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class Kernel extends BaseKernel
{
    public function registerBundles()
    {
        $contents = require $this->getProjectDir().'/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if ($envs[$this->environment] ?? $envs['all'] ?? false) {
                yield new $class();
            }
        }
    }

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

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getProjectDir().'/config/config_'.$this->getEnvironment().'.yaml');
    }
}
