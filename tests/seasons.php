<?php

class seasons extends PHPUnit_Framework_TestCase
{
    protected $season;

    public function setUp()
    {
        $this->season = new Jaybizzle\Seasons();
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
            array('June', 'Summer'),
            array('1st October 2016', 'Autumn'),
            array('31st December', 'Winter'),
        );
    }
}
