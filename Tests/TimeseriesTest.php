<?php

namespace Flagbit\Timeseries\Tests;

use Flagbit\Timeseries\Timeseries;

class TimeseriesTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $timeseries = new Timeseries(
            new \DateTime('now'),
            new \DateInterval('PT15M'),
            []
        );

        return $timeseries;
    }

    /**
     * @depends testConstruct
     */
    public function testClone(Timeseries $timeseries)
    {
        $clone = clone $timeseries;

        $this->assertNotSame($clone->getStart(), $timeseries->getStart());
    }

    public function testCount()
    {
        $timeseries = new Timeseries(
            new \DateTime('now'),
            new \DateInterval('PT15M'),
            [1, 2, 5]
        );

        $this->assertCount(3, $timeseries);
    }
}
