<?php

namespace App\Doctrine\DBAL\Event\Listeners;

use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Driver\AbstractMySQLDriver;
use Doctrine\DBAL\Event\ConnectionEventArgs;
use Doctrine\DBAL\Events;

class MysqlOnlyFullGroupByDisable implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [Events::postConnect];
    }

    /**
     * @param ConnectionEventArgs $args
     */
    public function postConnect(ConnectionEventArgs $args)
    {
        /*
        или это можно сделать глобально создав миграцию с
        SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));
         */

        if (!$args->getConnection()->getDriver() instanceof AbstractMySQLDriver) {
            return;
        }
        $args->getConnection()->executeQuery("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
    }
}
