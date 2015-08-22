<?php namespace Kevupton\Timetables\Repositories;

use Kevupton\BeastCore\Repositories\BeastRepository;
use Kevupton\Timetables\Exceptions\TimetableException;
use Kevupton\Timetables\Timetable;

class TimetableRepository extends BeastRepository
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
}