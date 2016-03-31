<?php namespace Kevupton\Timetables\Repositories;

use DateTime;
use Kevupton\Ethereal\Repositories\Repository;
use Kevupton\Timetables\Exceptions\TimetableDayException;
use Kevupton\Timetables\Timetable;
use Kevupton\Timetables\TimetableDay;

class TimetableDayRepository extends Repository
{

    public function __construct(Timetable $timetable) {
        parent::__construct($timetable);
        if (is_null($this->timetable)) $this->throwException('No timetable loaded');
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
     */
    public function isBookableTime($from, $to)
    {
        $from_time = to_time($from);
        $to_time = to_time($to);

        $day_from = to_day($from);
        $day_to = to_day($to);
        $date_from = to_date($from);
        $date_to = to_date($to);

        $query = $this->timetable->days();
        if ($date_from == $date_to) {
            return $query->where('day', $day_to)
                ->where('from', '<=', $from_time)
                ->where('to', '>=', $to_time)
                ->count() > 0;
        } else {
            $date1 = new DateTime($date_from);
            $date2 = new DateTime($date_to);

            $interval = $date1->diff($date2);

            $nb_days = $interval->days;
            if ($nb_days > 7) $nb_days = 7;
            $days = self::daysOfWeek();

            $index = array_search($day_from, $days);
            return
                $query->where(function($query) use ($days, $index, $nb_days, $from_time, $to_time) {
                    $query->orWhere(function($query) use ($days, $index, $from_time) {
                        $query->where('day', $days[$index])
                            ->where('from', '<=', $from_time)
                            ->where('to', '24:00:00.0000');
                    });
                    for ($i = 1; $i < $nb_days; $i++) {
                        $index++;
                        $index = $index%7;
                        $query->orWhere(function ($query) use ($days, $index) {
                            $query->where('day', $days[$index])
                                ->where('from', '00:00:00.0000')
                                ->where('to', '24:00:00.0000');
                        });
                    }
                    $index++;
                    $index = $index%7;
                    $query->orWhere(function($query) use ($days, $index, $to_time) {
                        $query->where('day', $days[$index])
                            ->where('from', '00:00:00.0000')
                            ->where('to', '>=', $to_time);
                    });
                })->count() == ($nb_days + 1);
        }
    }
}