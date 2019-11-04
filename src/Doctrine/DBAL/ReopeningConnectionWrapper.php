<?php

namespace App\Doctrine\DBAL;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;

/**
 * https://stackoverflow.com/questions/14258591/the-entitymanager-is-closed
 */
class ReopeningConnectionWrapper extends Connection
{
    public function insert($tableName, array $data, array $types = array())
    {
        try {
            parent::insert($tableName, $data, $types);
        } catch (DBALException $e) {
            if (!$this->isConnected()) {
                $this->connect();
            }
            throw new DBALException($e->getMessage());
        }
    }

}
