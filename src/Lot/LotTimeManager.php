<?php

namespace App\Lot;

class LotTimeManager
{
    const START_PERIOD_HOURS = 24 * 7;
    const PROLONG_PERIOD_HOURS = 12;

    /**
     * @return \DateTime
     */
    public function getDateCloseOnLotStart() : \DateTime
    {
        $dateClose = new \DateTime();
        $dateClose->modify('+ '.self::START_PERIOD_HOURS.' hours');
        $this->roundDateClose($dateClose);

        return $dateClose;
    }

    /**
     * @param \DateTime $oldDateClose
     * @return \DateTime
     */
    public function getDateCloseOnLotBetCreate(\DateTime $oldDateClose) : \DateTime
    {
        $newDateClose = new \DateTime();
        $newDateClose->modify('+ '.self::PROLONG_PERIOD_HOURS.' hours');
        $this->roundDateClose($newDateClose);

        if ($oldDateClose > $newDateClose) {
            return $oldDateClose;
        }

        return $newDateClose;
    }

    /**
     * @param \DateTime $dateTime
     */
    protected function roundDateClose(\DateTime $dateTime) : void
    {
        $dateTime->setTime($dateTime->format('H'), $dateTime->format('i'), 0);
    }

}
