<?php namespace Kevupton\Timetables;

use Kevupton\BeastCore\BeastModel;

class TimetableDay extends BeastModel {
    // table name
    protected $table = 'timetable_days';

    // validation rules
    public static $rules = array(
        'timetable_id' => 'required|integer|exists:timetables,id',
        'day' => 'required|in:MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY,SUNDAY',
        'from' => 'required|date',
        'to' => 'required|date'
    );

    protected $fillable = array(
        'timetable_id', 'day', 'from', 'to'
    );

    public static $relationsData = array(
        'timetable' => array(self::BELONGS_TO, Timetable::class)
    );
}
