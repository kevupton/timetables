<?php namespace Kevupton\Timetables\Repositories;

use Kevupton\Ethereal\Repositories\Repository;
use Kevupton\Timetables\Exceptions\TimetableSpecificException;
use Kevupton\Timetables\Timetable;
use Kevupton\Timetables\TimetableSpecific;

class TimetableSpecificRepository extends Repository
{

    public function __construct(Timetable $timetable) {
        if (is_null($timetable))
            $this->throwException("No timetable set");
        $this->timetable = $timetable;
        parent::__construct();
    }

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

    /**
     * Checks whether or not the specified time is marked as unavailable.
     *
     * @param $from
     * @param $to
     * @return bool
     */
    public function isUnavailable($from, $to)
    {
        return 0 < timetable_query_a(
            $this->timetable->specifics()
                ->where('is_available', 0),
            $from, $to
        );
    }

    /**
     * Checks whether or not the specified time is marked as available
     *
     * @param $from
     * @param $to
     * @return bool
     */
    public function isAvailable($from, $to)
    {
        return 0 < timetable_query_a(
            $this->timetable->specifics()
                ->where('is_available', 1),
            $from, $to
        );
    }
}