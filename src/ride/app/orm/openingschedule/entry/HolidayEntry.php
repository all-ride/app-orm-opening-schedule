<?php

namespace ride\app\orm\openingschedule\entry;

use ride\application\orm\entry\HolidayEntry as OrmHolidayEntry;

/**
 * HolidayEntry
 */
class HolidayEntry extends OrmHolidayEntry {

    /**
     * Get the start date
     *
     * @return DateTime
     */
    public function getStart() {
        $date = new \DateTime();
        $date->setTimestamp($this->getFrom());

        return $date;
    }

    /**
     * Get the end date
     *
     * @return integer
     */
    public function getEnd() {
        $date = new \DateTime();
        $date->setTimestamp($this->getTo());

        return $date;
    }

    /**
     * Check if this is the current Holiday
     *
     * @return boolean
     */
    public function isCurrent() {
        $now = time();

        return $this->getFrom() <= $now && $this->getTo() >= $now;
    }

}
