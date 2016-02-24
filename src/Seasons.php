<?php

namespace Jaybizzle;

class Seasons
{
    /**
     * Seasons.
     * 
     * @var array
     */
    public $seasons = array(
        'Winter',
        'Spring',
        'Summer',
        'Autumn',
    );

    public $methodology = 'meteorogical';

    /**
     * Month/Season map.
     * 
     * @var array
     */
    public $monthRange = array(
        0 => array(12, 1, 2),
        1 => array(3, 4, 5),
        2 => array(6, 7, 8),
        3 => array(9, 10, 11),
    );

    /**
     * Parse input date and return numeric month.
     * 
     * @param  string
     *
     * @return int
     */
    public function getMonth($date)
    {
        if (is_null($date)) {
            return date('n');
        } else {
            if ($parsed_date = strtotime($date)) {
                return date('n', strtotime($date));
            }

            throw new \Exception('Input date must be parsable by strtotime().');
        }
    }

    public function getYear($date)
    {
        if (is_null($date)) {
            return date('Y');
        } else {
            if ($parsed_date = strtotime($date)) {
                return date('Y', strtotime($date));
            }

            throw new \Exception('Input date must be parsable by strtotime().');
        }
    }

    /**
     * Parse date, return season.
     * 
     * @param  string
     *
     * @return string
     */
    public function get($date = null)
    {
        $method = 'get'.ucfirst($this->methodology);

        return $this->$method($date);
    }

    public function getMeteorogical($date)
    {
        return $this->seasons[(int) (($this->getMonth($date) % 12) / 3)];
    }

    public function getAstronomical($date)
    {
        $year = $this->getYear($date);
        $timestamps = $this->astronomicalTimestamps($year);

        $timestamps[] = strtotime($date);
        sort($timestamps);

        $date = (is_null($date)) ? date('d/m/Y', time()) : $date;

        if (max($timestamps) == strtotime($date)) {
            return 'Winter - hard';
        } else {
            return $this->seasons[array_search(strtotime($date), $timestamps)];
        }
    }

    public function astronomicalTimestamps($year)
    {
        $ts[] = $this->getSpringEquinox($year);
        $ts[] = $this->getAutumnEquinox($year);

        $soltices = $this->getSoltices($year);

        $ts[] = $soltices['shortest'];
        $ts[] = $soltices['longest'];

        return $ts;
    }

    /**
     * Get months numbers that belong to the season.
     * 
     * @param string $season
     *
     * @return array
     */
    public function monthRange($season)
    {
        if (!in_array(ucfirst($season), $this->seasons)) {
            throw new \Exception($season.' is not a season.');
        }

        return $this->monthRange[array_search(ucfirst($season), $this->seasons)];
    }

    /**
     * Modify season order to return correct season for southern hemisphere.
     * 
     * @return Jaybizzle\Season
     */
    public function southern()
    {
        for ($i = 0; $i < 2; $i++) {
            array_push($this->seasons, array_shift($this->seasons));
        }

        return $this;
    }

    public function astronomical()
    {
        $this->methodology = 'astronomical';

        return $this;
    }

    /**
     * @Get Timestamp for Vernal Equinox
     * beginning of spring in the Northern Hemisphere and autumn in the Southern Hemisphere.
     *
     * @param int $year
     *
     * @return int
     */
    public function getSpringEquinox($year, $timezone = 'Etc/GMT')
    {
        return $this->getEquinox(79.3125, $year);
    }

    public function getAutumnEquinox($year, $timezone = 'Etc/GMT')
    {
        return $this->getEquinox(265.718, $year);
    }

    public function getEquinox($days, $year)
    {
        date_default_timezone_set($timezone);
        /*** the base gmt time ***/
        $gmt = gmmktime(0, 0, 0, 1, 1, 2000);

        $days_from_base = $days + ($year - 2000) * 365.2425;
        $seconds_from_base = $days_from_base * 86400;

        $equinox = round($gmt + $seconds_from_base);

        return $equinox;
    }

    public function getSoltices($year)
    {
        date_default_timezone_set('UTC');
        $date = $year.'/01/01';

        $end_date = $year.'/12/31';
        $i = 0;
        //loop through the year
        while (strtotime($date) <= strtotime($end_date)) {
            $sunrise = date_sunrise(strtotime($date), SUNFUNCS_RET_DOUBLE, 31.47, 35.13, 90, 3);
            $sunset = date_sunset(strtotime($date), SUNFUNCS_RET_DOUBLE, 31.47, 35.13, 90, 3);
            //calculate time difference
            $delta = $sunset - $sunrise;
            //store the time difference
            $delta_array[$i] = $delta;
            //store the date
            $dates_array[$i] = strtotime($date);
            $i++;
            //next day
            $date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
        }

        $dates['shortest'] = $dates_array[array_search(min($delta_array), $delta_array)];
        $dates['longest'] = $dates_array[array_search(max($delta_array), $delta_array)];

        return $dates;
    }
}
