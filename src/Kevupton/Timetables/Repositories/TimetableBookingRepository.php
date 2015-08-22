<?php namespace Kevupton\Timetables\Repositories;

use Kevupton\BeastCore\Repositories\BeastRepository;
use Kevupton\Timetables\Exceptions\TimetableDayException;
use Kevupton\Timetables\TimetableBooking;

class TimetableBookingRepository extends BeastRepository
{
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
}