<?php namespace Kevupton\Timetables\Repositories;

use Kevupton\Ethereal\Repositories\Repository;
use Kevupton\Timetables\Exceptions\TimetableException;
use Kevupton\Timetables\Timetable;

class TimetableRepository extends Repository
{
    protected $exceptions = [
        'main' => TimetableException::class
    ];

    /**
     * Retrieves the class instance of the specified repository.
     *
     * @return string the string instance of the defining class
     */
    function getClass()
    {
        return Timetable::class;
    }

    /**
     * Returns the timetableDay repository.
     *
     * @param mixed $timetable
     * @return TimetableDayRepository
     * @throws TimetableException if the value is invalid
     */
    public function timetableDayRepository($timetable = null) {
        $this->load($timetable);
        return new TimetableDayRepository($this->timetable);
    }

    /**
     * Checks to see if the
     * @param $from
     * @param $to
     * @param null $timetable
     * @return bool
     * @internal param $datetime
     */
    public function isAvailable($from, $to, $timetable = null) {
        $this->load($timetable);
        $day_repo = $this->timetableDayRepository();
        $bookings_repo = $this->timetableBookingRepository();
        $specifics_repo = $this->timetableSpecificRepository();

        return (($day_repo->isBookableTime($from, $to) || $specifics_repo->isAvailable($from, $to))
            && !$bookings_repo->isBooked($from, $to)
            && !$specifics_repo->isUnavailable($from, $to));
    }

    /**
     * returns the timetableBookingRepository
     *
     * @param mixed $timetable
     * @return TimetableBookingRepository
     */
    public function timetableBookingRepository($timetable = null) {
        $this->load($timetable);
        return new TimetableBookingRepository($this->timetable);
    }


    /**
     * Returns the timetableSpecificRepository
     *
     * @param mixed $timetable
     * @return TimetableSpecificRepository
     */
    public function timetableSpecificRepository($timetable = null)
    {
        $this->load($timetable);
        return new TimetableSpecificRepository($this->timetable);
    }
}