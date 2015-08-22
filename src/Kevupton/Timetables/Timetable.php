<?php namespace Kevupton\Timetables;

use Kevupton\BeastCore\BeastModel;

class Timetable extends BeastModel {
    // table name
    protected $table = 'timetables';

    // validation rules
    public static $rules = array(
        'for_id' => 'required|integer',
        'for_type' => 'required|string|max:128'
    );

    protected $fillable = array(
        'for_id', 'for_type'
    );

    public static $relationsData = array(
        'for' => array(self::MORPH_TO),
        'days' => array(self::HAS_MANY)
    );
}
