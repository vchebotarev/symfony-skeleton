<?php

namespace App\Timezone;

use App\Doctrine\Entity\User;

class TimezoneManager
{
    /**
     * @var string
     */
    protected $defaultTimezone;

    public function __construct()
    {
        $this->defaultTimezone = ini_get('date.timezone');
    }

    /**
     * @return \DateTime[]
     * @link http://php.net/manual/en/datetimezone.listidentifiers.php
     */
    public function getList()
    {
        $tzList = [];
        foreach (\DateTimeZone::listIdentifiers() as $zone) {
            $tzList[$zone] = new \DateTime('now', new \DateTimeZone($zone));
        }
        //unset($tzList['UTC']);

        return $tzList;
    }

    /**
     * @return \DateTime[]
     * @link http://php.net/manual/en/datetimezone.listidentifiers.php
     */
    public function getListOnlyRound()
    {
        $tzList = [];
        foreach (\DateTimeZone::listIdentifiers() as $zone) {
            $tz = new \DateTime('now', new \DateTimeZone($zone));
            if ($tz->format('O') % 100 != 0) {
                continue;
            }
            $tzList[$zone] = $tz;
        }
        //unset($tzList['UTC']);

        return $tzList;
    }

    /**
     * @param User $user
     * @return string
     */
    public function getTimezoneByUser(User $user) : string
    {
        $tz = $user->getTimezone();
        if (!$tz) {
            $tz = $this->defaultTimezone;
        }
        return $tz;
    }

    /**
     * @return string
     */
    public function getDefaultTimezone() : string
    {
        return $this->defaultTimezone;
    }
}
