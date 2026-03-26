<?php

namespace Jaybizzle;

class Seasons
{
    const SEASON_WINTER = 'Winter';

    const SEASON_SPRING = 'Spring';

    const SEASON_SUMMER = 'Summer';

    const SEASON_AUTUMN = 'Autumn';

    /**
     * Create a new Seasons instance.
     *
     * @param  array|null $names Season names in order: [winter, spring, summer, autumn]
     */
    public function __construct(?array $names = null)
    {
        if (! is_null($names)) {
            if (count($names) !== 4) {
                throw new \Exception('Season names array must contain exactly 4 elements: [winter, spring, summer, autumn].');
            }

            $this->seasons = array_values($names);
        }
    }

    /**
     * Whether to use astronomical season calculation.
     *
     * @var bool
     */
    protected $useAstronomical = false;

    /**
     * Whether to use southern hemisphere seasons.
     *
     * @var bool
     */
    protected $useSouthern = false;

    /**
     * Seasons.
     *
     * @var array
     */
    protected $seasons = array(
        self::SEASON_WINTER,
        self::SEASON_SPRING,
        self::SEASON_SUMMER,
        self::SEASON_AUTUMN,
    );

    /**
     * Month/Season map.
     *
     * @var array
     */
    protected $monthMap = array(
        0 => array(12, 1, 2),
        1 => array(3, 4, 5),
        2 => array(6, 7, 8),
        3 => array(9, 10, 11),
    );

    /**
     * Parse input date and return numeric month.
     *
     * @param  string|null $date
     *
     * @return int
     */
    protected function parseMonth($date)
    {
        if (is_null($date)) {
            return date('n');
        }

        if ($parsed_date = strtotime($date)) {
            return date('n', $parsed_date);
        }

        throw new \Exception('Input date must be parsable by strtotime().');
    }

    /**
     * Get the season for a given date.
     *
     * @param  string|null $date
     * @return string
     */
    public function season($date = null)
    {
        if ($this->useAstronomical) {
            return $this->seasonAstronomical($date);
        }

        $index = (int) (($this->parseMonth($date) % 12) / 3);

        return $this->seasons[($index + $this->hemisphereOffset()) % 4];
    }

    /**
     * Get season using astronomical (equinox/solstice) boundaries.
     *
     * @param  string|null $date
     * @return string
     */
    protected function seasonAstronomical($date)
    {
        $timestamp = is_null($date) ? time() : strtotime($date);

        if (! $timestamp) {
            throw new \Exception('Input date must be parsable by strtotime().');
        }

        $dayOfYear = (int) date('z', $timestamp) + 1;
        $isLeap = (int) date('L', $timestamp);

        $springStart = 80 + $isLeap;
        $summerStart = 172 + $isLeap;
        $autumnStart = 266 + $isLeap;
        $winterStart = 356 + $isLeap;

        if ($dayOfYear >= $springStart && $dayOfYear < $summerStart) {
            $index = 1;
        } elseif ($dayOfYear >= $summerStart && $dayOfYear < $autumnStart) {
            $index = 2;
        } elseif ($dayOfYear >= $autumnStart && $dayOfYear < $winterStart) {
            $index = 3;
        } else {
            $index = 0;
        }

        return $this->seasons[($index + $this->hemisphereOffset()) % 4];
    }

    /**
     * Get month numbers that belong to the season.
     *
     * @param string $season
     * @return array
     */
    public function monthRange($season)
    {
        $index = array_search($season, $this->seasons);

        if ($index === false) {
            $index = array_search(ucfirst($season), $this->seasons);
        }

        if ($index === false) {
            throw new \Exception($season.' is not a season.');
        }

        return $this->monthMap[($index + $this->hemisphereOffset()) % 4];
    }

    /**
     * Return a new instance configured for astronomical (equinox/solstice) season boundaries.
     *
     * @return Jaybizzle\Seasons
     */
    public function astronomical()
    {
        $new = clone $this;
        $new->useAstronomical = true;

        return $new;
    }

    /**
     * Return a new instance configured for southern hemisphere.
     *
     * @return Jaybizzle\Seasons
     */
    public function southern()
    {
        $new = clone $this;
        $new->useSouthern = true;

        return $new;
    }

    /**
     * Get the hemisphere offset for season index.
     *
     * @return int
     */
    protected function hemisphereOffset()
    {
        return $this->useSouthern ? 2 : 0;
    }

    /**
     * Get the season for the current date.
     *
     * @return string
     */
    public static function now(?array $names = null)
    {
        return (new static($names))->season();
    }

    /**
     * Get the season for a given date.
     *
     * @param  string $date
     * @param  array|null $names Season names in order: [winter, spring, summer, autumn]
     * @return string
     */
    public static function for($date, ?array $names = null)
    {
        return (new static($names))->season($date);
    }
}
