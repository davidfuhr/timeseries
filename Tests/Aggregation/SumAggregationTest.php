<?php

namespace Flagbit\Timeseries\Tests\Aggregation;

use ArrayObject;
use Flagbit\Timeseries\Aggregation\SumAggregation;

class SumAggregationTest extends \PHPUnit_Framework_TestCase
{
    public function provideTestSum()
    {
        return [
            [[1, 1, 2, 3, 5, 8, 13, 21], 54],
            [[0.1, 0.00001], 0.10001],
        ];
    }

    /**
     * @dataProvider provideTestSum
     */
    public function testSum($values, $expected)
    {
        $traversable = new ArrayObject($values);

        $sum = new SumAggregation();
        $actual = $sum->aggregate($traversable);

        $this->assertEquals($expected, $actual);
    }
}
