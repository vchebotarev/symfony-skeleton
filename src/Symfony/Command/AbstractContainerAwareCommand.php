<?php

namespace App\Symfony\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

abstract class AbstractContainerAwareCommand extends ContainerAwareCommand
{
    /**
     * @todo неправильно распознает Symfony plugin
     * Returns true if the service id is defined.
     * @param string $id The service id
     * @return bool true if the service id is defined, false otherwise
     */
    protected function has($id)
    {
        return $this->getContainer()->has($id);
    }

    /**
     * @todo неправильно распознает Symfony plugin
     * Gets a container service by its id.
     * @param string $id The service id
     * @return object The service
     */
    protected function get($id)
    {
        return $this->getContainer()->get($id);
    }

    /**
     * @todo неправильно распознает Symfony plugin
     * Gets a container configuration parameter by its name.
     * @param string $name The parameter name
     * @return mixed
     */
    protected function getParameter($name)
    {
        return $this->getContainer()->getParameter($name);
    }
}
