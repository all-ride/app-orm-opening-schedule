<?php

namespace ride\app\orm\openingschedule\entry;

use ride\application\orm\entry\OpeningHourEntry as OrmOpeningHourEntry;

/**
 * OpeningHourEntry
 */
class OpeningHourEntry extends OrmOpeningHourEntry {

    private static $weekdays = [1=>'Mon',2=>'Tue',3=>'Wed',4=>'Thu',5=>'Fri',6=>'Sat',7=>'Sun'];

    /**
     * Get the start date
     *
     * @return integer
     */
    public function getStart() {
        $date = new \DateTime();

        if (date('N', time()) !== $this->getWeekday()) {
            $time = strtotime('next ' . self::$weekdays[$this->getWeekday()]);
            $date->setTimestamp($time);
        }

        $from = $this->getFrom();
        $hour = floor($from / 60 / 60);
        $min = ($from - ($hour * 60 * 60)) / 60;
        $date->setTime($hour, $min);

        return $date;
    }

    /**
     * Get the end date
     *
     * @return integer
     */
    public function getEnd() {
        $date = new \DateTime();
        
        if (date('N', time()) !== $this->getWeekday()) {
            $time = strtotime('next ' . self::$weekdays[$this->getWeekday()]);
            $date->setTimestamp($time);
        }

        $to = $this->getTo();
        $hour = floor($to / 60 / 60);
        $min = ($to - ($hour * 60 * 60)) / 60;
        $date->setTime($hour, $min);

        return $date;
    }

    /**
     * Get the next start date
     *
     * @return integer
     */
    public function getNextStart() {
        $date = new \DateTime();
        $date->setTimestamp(strtotime('next ' . self::$weekdays[$this->getWeekday()]));

        $from = $this->getFrom();
        $hour = floor($from / 60 / 60);
        $min = ($from - ($hour * 60 * 60)) / 60;
        $date->setTime($hour, $min);

        return $date;
    }

    /**
     * Get the next end date
     *
     * @return integer
     */
    public function getNextEnd() {
        $date = new \DateTime();
        $date->setTimestamp(strtotime('next ' . self::$weekdays[$this->getWeekday()]));

        $to = $this->getTo();
        $hour = floor($to / 60 / 60);
        $min = ($to - ($hour * 60 * 60)) / 60;
        $date->setTime($hour, $min);

        return $date->getTimestamp();
    }

    /**
     * Check if this is the current OpeningHour: today and current time
     *
     * @return boolean
     */
    public function isCurrent() {
        $now = time() - strtotime('today');

        return date('N', time()) === $this->getWeekday() && $this->getFrom() <= $now && $this->getTo() >= $now;
    }

}
