<?php

use PHPUnit\Framework\TestCase;
use Jaybizzle\Seasons;

class SeasonsTest extends TestCase
{
    protected $season;

    public function setUp()
    {
        $this->season = new Jaybizzle\Seasons();
    }

    /**
     * @dataProvider fussyDateStrings
     */
    public function testMustProvideSeasonByDateTime(
        $dateTimeValue,
        $expected
    ) {
        $dateTimeObject = new \DateTime($dateTimeValue);
        $result = $this->season->getSeasonFromDateTime($dateTimeObject);
        $this->assertequals($expected, $result);
    }

    public function fussyDateStrings()
    {
        return array(
            array('20st March', Seasons::SEASON_WINTER),
            array('21st March', Seasons::SEASON_SPRING),
        );
    }

    /**
     * @dataProvider dateStrings
     */
    public function testDateParsing($original, $expected)
    {
        $result = $this->season->get($original);
        $this->assertEquals($expected, $result);
    }

    public function dateStrings()
    {
        return array(
            array('20st March', Seasons::SEASON_WINTER),
            array('21st March', Seasons::SEASON_SPRING),
            array('25st July', Seasons::SEASON_SUMMER),
            array('June', 'Spring'), // june starts in spring and finish in summer
            array('1st October 2016', 'Autumn'),
            array('31st December', 'Winter'),
        );
    }

    public function testReturnCurrentMonthNumber()
    {
        $currentMonthNumber = date('n');

        $this->assertEquals(
            $currentMonthNumber,
            $this->season->getMonth(null)
        );
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Input date must be parsable by strtotime().
     */
    public function testThrowExceptionWithInvalidMontValue()
    {
        $this->season->getMonth('invalid month value');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage invalid month range is not a season.
     */
    public function testThrowExceptionWithInvalidMontRange()
    {
        $this->season->monthRange('invalid month range');
    }

    public function testProvideSeasonsReange()
    {
        $winterRange = $this->season->monthRange(Jaybizzle\Seasons::SEASON_WINTER);
        $this->assertEquals(
            array(12, 1, 2),
            $winterRange
        );
    }

    public function testProvideSeasonsReangeInSouthernRegionOfTheWorld()
    {
        $winter = $this->season->monthRange(Jaybizzle\Seasons::SEASON_WINTER);

        $season = $this->season->southern();

        $southernSpring = $season->monthRange(Jaybizzle\Seasons::SEASON_SUMMER);

        $this->assertEquals(
            $winter,
            $southernSpring
        );
    }
}
