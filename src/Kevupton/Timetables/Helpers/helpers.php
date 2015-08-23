<?php

/**
* Converts a datetime to its time equivalent only.
* @param string $datetime converts a string datetime, or any string date, to a time string.
* @return string
*/
if (!function_exists('to_time')) {
    function to_time($datetime)
    {
        return date('H:i:s', strtotime($datetime));
    }
}

/**
* Converts a datetime to the uppercase day value
* @param $datetime
* @return string
*/
if (!function_exists('to_day')) {
    function to_day($datetime)
    {
        return strtoupper(date('l', strtotime($datetime)));
    }
}

if (!function_exists('timetable_query_a')) {
    function timetable_query_a($query, $from, $to)
    {
        return $query
            ->where(function($query) use($from, $to) {
                $query
                    ->orWhere(function ($query) use ($from, $to) {
                        $query->orWhere(function ($query) use ($from) {
                            $query->where('from', '<', $from)
                                ->where('to', '>', $from);
                            })
                            ->orWhere(function ($query) use ($to) {
                                $query->where('from', '<', $to)
                                    ->where('to', '>', $to);
                            });
                    })
                    ->orWhere(function ($query) use ($from, $to) {
                        $query->orWhere(function ($query) use ($from, $to) {
                            $query->where('from', '>', $from)
                                ->where('from', '<', $to);
                            })
                            ->orWhere(function ($query) use ($from, $to) {
                                $query->where('to', '>', $from)
                                    ->where('to', '<', $to);
                            });
                    });
            })->count();
    }
}
