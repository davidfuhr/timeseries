<?php

namespace Flagbit\Timeseries\Tests\Aggregation;

use ArrayObject;
use Flagbit\Timeseries\Aggregation\MaxAggregation;

class MaxAggregationTest extends \PHPUnit_Framework_TestCase
{
    public function provideTestMax()
    {
        return [
            [[1, 1, 2, 3, 5, 8, 13, 21], 21],
            [[0.1, 0.00001], 0.1],
        ];
    }

    /**
     * @dataProvider provideTestMax
     */
    public function testMax($values, $expected)
    {
        $traversable = new ArrayObject($values);

        $sum = new MaxAggregation();
        $actual = $sum->aggregate($traversable);

        $this->assertEquals($expected, $actual);
    }
}
