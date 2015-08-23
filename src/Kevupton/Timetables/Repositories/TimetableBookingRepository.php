<?php namespace Kevupton\Timetables\Repositories;

use Kevupton\BeastCore\Repositories\BeastRepository;
use Kevupton\Timetables\Exceptions\TimetableDayException;
use Kevupton\Timetables\Timetable;
use Kevupton\Timetables\TimetableBooking;

class TimetableBookingRepository extends BeastRepository
{

    public function __construct(Timetable $timetable) {
        if (is_null($timetable))
            $this->throwException("No timetable set");
        $this->timetable = $timetable;;
    }

    protected $exceptions = [
        'main' => TimetableDayException::class
    ];

    /**
     * Retrieves the class instance of the specified repository.
     *
     * @return string the string instance of the defining class
     */
    function getClass()
    {
        return TimetableBooking::class;
    }

    /**
     * Checks whether or not the datetime is currently booked.
     *
     * @param $from
     * @param $to
     * @return bool
     */
    public function isBooked($from, $to)
    {
        return 0 < timetable_query_a($this->timetable->bookings(), $from, $to);
    }
}