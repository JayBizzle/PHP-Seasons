<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class SeasonsTest extends TestCase
{
    protected $season;

    public function setUp(): void
    {
        $this->season = new Jaybizzle\Seasons();
    }

    #[DataProvider('dateStrings')]
    public function testDateParsing($original, $expected)
    {
        $result = $this->season->season($original);
        $this->assertEquals($expected, $result);
    }

    public static function dateStrings()
    {
        return array(
            array('June', 'Summer'),
            array('1st October 2016', 'Autumn'),
            array('31st December', 'Winter'),
        );
    }

    public function testThrowExceptionWithInvalidDate()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Input date must be parsable by strtotime().');
        $this->season->season('invalid month value');
    }

    public function testThrowExceptionWithInvalidMonthRange()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('invalid month range is not a season.');
        $this->season->monthRange('invalid month range');
    }

    public function testMonthRange()
    {
        $winterRange = $this->season->monthRange(Jaybizzle\Seasons::SEASON_WINTER);
        $this->assertEquals(
            array(12, 1, 2),
            $winterRange
        );
    }

    #[DataProvider('astronomicalDateStrings')]
    public function testAstronomicalDateParsing($original, $expected)
    {
        $result = $this->season->astronomical()->season($original);
        $this->assertEquals($expected, $result);
    }

    public static function astronomicalDateStrings()
    {
        return array(
            array('March 19 2024', 'Winter'),
            array('March 21 2024', 'Spring'),
            array('June 20 2024', 'Spring'),
            array('June 22 2024', 'Summer'),
            array('September 22 2024', 'Summer'),
            array('September 24 2024', 'Autumn'),
            array('December 21 2024', 'Autumn'),
            array('December 23 2024', 'Winter'),
            array('January 15 2024', 'Winter'),
            array('April 15 2024', 'Spring'),
            array('July 15 2024', 'Summer'),
            array('October 15 2024', 'Autumn'),
        );
    }

    public function testAstronomicalWithSouthernHemisphere()
    {
        $result = $this->season->astronomical()->southern()->season('March 25 2024');
        $this->assertEquals('Autumn', $result);
    }

    public function testSouthernDoesNotMutateOriginal()
    {
        $southern = $this->season->southern();
        $this->assertEquals('Summer', $this->season->season('July 15 2024'));
        $this->assertEquals('Winter', $southern->season('July 15 2024'));
    }

    public function testAstronomicalDoesNotMutateOriginal()
    {
        $astronomical = $this->season->astronomical();
        $this->assertEquals('Spring', $this->season->season('March 19 2024'));
        $this->assertEquals('Winter', $astronomical->season('March 19 2024'));
    }

    public function testSouthernMonthRange()
    {
        $southern = $this->season->southern();
        $this->assertEquals(
            array(12, 1, 2),
            $southern->monthRange(Jaybizzle\Seasons::SEASON_SUMMER)
        );
    }

    public function testStaticNow()
    {
        $result = Jaybizzle\Seasons::now();
        $this->assertContains($result, array('Winter', 'Spring', 'Summer', 'Autumn'));
    }

    public function testStaticFor()
    {
        $result = Jaybizzle\Seasons::for('June');
        $this->assertEquals('Summer', $result);
    }

    public function testCustomSeasonNames()
    {
        $french = new Jaybizzle\Seasons(array('Hiver', 'Printemps', 'Été', 'Automne'));
        $this->assertEquals('Été', $french->season('June'));
        $this->assertEquals('Hiver', $french->season('January'));
    }

    public function testCustomNamesWithSouthern()
    {
        $french = new Jaybizzle\Seasons(array('Hiver', 'Printemps', 'Été', 'Automne'));
        $this->assertEquals('Hiver', $french->southern()->season('June'));
    }

    public function testCustomNamesWithAstronomical()
    {
        $french = new Jaybizzle\Seasons(array('Hiver', 'Printemps', 'Été', 'Automne'));
        $this->assertEquals('Hiver', $french->astronomical()->season('March 19 2024'));
        $this->assertEquals('Printemps', $french->astronomical()->season('March 21 2024'));
    }

    public function testCustomNamesMonthRange()
    {
        $french = new Jaybizzle\Seasons(array('Hiver', 'Printemps', 'Été', 'Automne'));
        $this->assertEquals(array(6, 7, 8), $french->monthRange('Été'));
    }

    public function testCustomNamesRequireExactlyFour()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Season names array must contain exactly 4 elements');
        new Jaybizzle\Seasons(array('One', 'Two'));
    }

    public function testStaticForWithCustomNames()
    {
        $result = Jaybizzle\Seasons::for('June', array('Hiver', 'Printemps', 'Été', 'Automne'));
        $this->assertEquals('Été', $result);
    }
}
