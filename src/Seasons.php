<?php

namespace Jaybizzle;

class Seasons
{
    /**
     * Seasons.
     * 
     * @var array
     */
    public $seasons = [
        'Winter',
        'Spring',
        'Summer',
        'Autumn',
    ];

    /**
     * Parse input date and return numeric month.
     * 
     * @param  string
     * @return int
     */
    public function getMonth($date)
    {
        if(is_null($date)) {
            return date('n');
        } else {
            return date('n', strtotime($date));
        }
    }

    /**
     * Parse date, return season.
     * 
     * @param  string
     * @return string
     */
    public function get($date = null)
    {
        return $this->seasons[(int)(($this->getMonth($date) %12)/3)];
    }
}
