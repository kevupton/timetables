<?php namespace Kevupton\Timetables\Repositories;

use Kevupton\BeastCore\Repositories\BeastRepository;
use Kevupton\Timetables\Exceptions\TimetableExceptionException;
use Kevupton\Timetables\TimetableDay;

class TimetableExceptionRepository extends BeastRepository
{
    protected $exceptions = [
        'main' => TimetableExceptionException::class
    ];

    /**
     * Retrieves the class instance of the specified repository.
     *
     * @return string the string instance of the defining class
     */
    function getClass()
    {
        return Time::class;
    }
}