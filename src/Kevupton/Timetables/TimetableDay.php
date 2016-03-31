<?php namespace Kevupton\Timetables;


use Kevupton\Ethereal\Models\Ethereal;

class TimetableDay extends Ethereal {
    // table name
    protected $table = 'timetable_days';
    public $timestamps = false;

    // validation rules
    public static $rules = array(
        'timetable_id' => 'required|integer|exists:timetables,id',
        'day' => 'required|in:MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY,SUNDAY',
        'from' => 'required',
        'to' => 'required'
    );

    protected $fillable = array(
        'timetable_id', 'day', 'from', 'to'
    );

    public static $relationsData = array(
        'timetable' => array(self::BELONGS_TO, Timetable::class)
    );
}
