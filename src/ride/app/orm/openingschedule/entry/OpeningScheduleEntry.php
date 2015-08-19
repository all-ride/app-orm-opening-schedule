<?php

namespace ride\app\orm\openingschedule\entry;

use ride\application\orm\entry\OpeningScheduleEntry as OrmOpeningScheduleEntry;

/**
 * OpeningScheduleEntry
 */
class OpeningScheduleEntry extends OrmOpeningScheduleEntry {

    /**
     * Get openingHours by a specific day
     *
     * @param  int $day Day number
     * @return array
     */
    public function getOpeningHoursByDay($day) {
        $openingHours = $this->getOpeningHours();
        $filtered = [];

        foreach ($openingHours as $openingHour) {
            if ($openingHour->getWeekday() === $day) {
                $filtered[$openingHour->getFrom()] = $openingHour;
            }
        }

        return $filtered;
    }

    /**
     * Get an OpeningHour by timestamp
     *
     * @param int $timestamp Timestamp to check
     * @return mixed
     */
    public function getOpeningHour($timestamp) {
        if (!$this->isEnabled() || $this->getCurrentHoliday()) {
            return false;
        }

        $openingHours = $this->getOpeningHoursByDay(date('N', $timestamp));
        foreach($openingHours as $openingHour) {
            if ($openingHour->getStart()->getTimestamp() <= $timestamp && $timestamp <= $openingHour->getEnd()->getTimestamp()) {
                return $openingHour;
            }
        }

        return false;
    }

    /**
     * Get the current OpeningHour
     *
     * @return mixed
     */
    public function getCurrentOpeningHour() {
        return $this->getOpeningHour(time());
    }

    /**
     * Get the next openingHour
     *
     * @return mixed
     */
    public function getNextOpeningHour() {
        if (!$this->isEnabled()) {
            return false;
        }

        $result = false;

        $openingHours = $this->getSortedOpeningHours();
        foreach($openingHours as $openingHour) {
            if (!$openingHour->isCurrent()) {
                $result = $openingHour;
            }
        }

        return $result;
    }

    /**
     * Get sorted OpeningHours
     * OpeningHours are not just sorted by day of week and timestamp, but also by the current day.
     * eg. If it's Tuesday, next week Monday will be the last in line.
     *
     * @return array
     */
    protected function getSortedOpeningHours() {
        $openingHours = $this->getOpeningHours();

        $sorted = [];
        foreach ($openingHours as $openingHour) {
            $sorted[$openingHour->getStart()->getTimestamp()] = $openingHour;
        }
        ksort($sorted);

        return $sorted;
    }

    /**
     * Get a Holiday by timestamp
     *
     * @param int $timestamp Timestamp to check
     * @return mixed
     */
    public function getHoliday($timestamp) {
        if (!$this->isEnabled()) {
            return false;
        }

        $holidays = $this->getHolidays();
        foreach($holidays as $holiday) {
            if($holiday->getFrom() <= $timestamp && $timestamp <= $holiday->getTo()) {
                return $holiday;
            }
        }

        return false;
    }

    /**
     * Get the Holiday
     *
     * @return mixed
     */
    public function getCurrentHoliday() {
        return $this->getHoliday(time());
    }

    /**
     * Get the next Holiday
     *
     * @return mixed
     */
    public function getNextHoliday() {
        if (!$this->isEnabled()) {
            return false;
        }

        $holidays = $this->getHolidays();
        foreach($holidays as $holiday) {
            if (!$holiday->isCurrent()) {
                return $holiday;
            }
        }

        return false;
    }

    /**
     * Override the setOpeningHours method from ride\application\orm\entry\OpeningScheduleEntry because of nested form collections
     *
     * @param array $openingHours
     * @return null
     */
     public function setOpeningHours(array $openingHours = array()) {
         $this->openingHours = $openingHours;

         if ($this->entryState === self::STATE_CLEAN) {
             $this->entryState = self::STATE_DIRTY;
         }
     }

    /**
     * Override the setHolidays method from ride\application\orm\entry\OpeningScheduleEntry because of nested form collections
     *
     * @param array $holidays
     * @return null
     */
     public function setHolidays(array $holidays = array()) {
         $this->holidays = $holidays;

         if ($this->entryState === self::STATE_CLEAN) {
             $this->entryState = self::STATE_DIRTY;
         }
     }
}
