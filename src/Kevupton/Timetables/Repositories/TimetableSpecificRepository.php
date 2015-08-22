<?php namespace Kevupton\Timetables\Repositories;

use Kevupton\BeastCore\Repositories\BeastRepository;
use Kevupton\Timetables\Exceptions\TimetableSpecificException;
use Kevupton\Timetables\TimetableSpecific;

class TimetableSpecificRepository extends BeastRepository
{
    protected $exceptions = [
        'main' => TimetableSpecificException::class
    ];

    /**
     * Retrieves the class instance of the specified repository.
     *
     * @return string the string instance of the defining class
     */
    function getClass()
    {
        return TimetableSpecific::class;
    }
}