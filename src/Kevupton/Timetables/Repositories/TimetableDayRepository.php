<?php namespace Kevupton\Timetables\Repositories;

use Kevupton\BeastCore\Repositories\BeastRepository;
use Kevupton\Timetables\Exceptions\TimetableDayException;
use Kevupton\Timetables\Timetable;
use Kevupton\Timetables\TimetableDay;

class TimetableDayRepository extends BeastRepository
{
    private $timetable;

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
        return TimetableDay::class;
    }

    /**
     * Gets the times that a timetable is available for.
     *
     * @param string $day the day of the week uppercase whole word
     * @return array
     */
    public function getAvailabilityTimes($day) {
        $times = [];
        foreach ($this->cache($day, $this->getDayTimes($day)) as $day) {
            $times[] =  ['from' => $day->from, 'to' => $day->to];
        }
        return $times;
    }

    /**
     * Gets all the times for the specific  day of a specific timetable.
     *
     * @param string $day
     * @return mixed returns a list of all the days.
     */
    public function getDayTimes($day) {
        if (!self::validDay($day)) $this->throwException("Invalid day: $day");
        $day = strtoupper($day);
        return $this->timetable->days()->where('day', $day)->get();
    }

    /**
     * Returns whether or not the specified day is a valid day of the week
     *
     * @param $day
     * @return bool
     */
    public static function validDay($day) {
        return in_array(strtoupper($day), self::daysOfWeek());
    }

    /**
     * Gets all the days and caches the result.
     *
     * @return array of all the days
     */
    public function days() {
        return $this->cache('days', $this->timetable->days);
    }

    public static function loadTimetable(Timetable $timetable) {
        return new TimetableDayRepository($timetable);
    }

    /**
     * Returns the days of the week
     *
     * @return array the days of the week
     */
    public static function daysOfWeek() {
        return ['MONDAY', "TUESDAY", "WEDNESDAY", "THURSDAY", "FRIDAY", 'SATURDAY', 'SUNDAY'];
    }

    /**
     * Checks if the specific datetime is a valid time to book, according to the days.
     *
     * @param $from
     * @param $to
     * @return bool
     * @internal param $datetime
     */
    public function isBookableTime($from, $to)
    {
        /* TODO: Create the different days problem */

        $from_time = to_time($from);
        $to_time = to_time($to);
        $day_from = to_day($from);
        $day_to = to_day($to);

        return 0 < timetable_query_a(
            $this->timetable->days()
                ->where('day', $day_to),
            $from_time, $to_time
        );
    }
}