<?php namespace Kevupton\Timetables;

use Kevupton\BeastCore\BeastModel;

class TimetableSpecific extends BeastModel {
    // table name
    protected $table = 'timetable_specifics';

    // validation rules
    public static $rules = array(
        'timetable_id' => 'required|integer|exists:timetables,id',
        'is_available' => 'required|boolean',
        'from' => 'required|date',
        'to' => 'required|date'
    );

    protected $fillable = array(
        'timetable_id', 'is_available', 'from', 'to'
    );

    public static $relationsData = array(
        'timetable' => array(self::BELONGS_TO, Timetable::class)
    );
}
