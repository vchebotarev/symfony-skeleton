<?php

namespace App\User\Online\Adapter;

interface AdapterInterface
{

    /**
     * @param int $id
     * @return bool
     */
    public function isOnline(int $id) : bool;

    /**
     * @param int $id
     * @return $this
     */
    public function setOnline(int $id);

    /**
     * @param int $id
     * @return $this
     */
    public function unsetOnline(int $id);

    /**
     * @return int
     */
    public function countOnline() : int;

    /**
     * @return iterable
     */
    public function getOnline() : iterable;

}
