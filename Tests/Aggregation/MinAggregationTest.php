<?php

namespace Flagbit\Timeseries\Tests\Aggregation;

use ArrayObject;
use Flagbit\Timeseries\Aggregation\MinAggregation;

class MinAggregationTest extends \PHPUnit_Framework_TestCase
{
    public function provideTestMin()
    {
        return [
            [[1, 1, 2, 3, 5, 8, 13, 21], 1],
            [[0.1, 0.00001], 0.00001],
            [[-31, 51, null], -31],
            [[53, 2, 0.00001, null], 0.00001],
        ];
    }

    /**
     * @dataProvider provideTestMin
     */
    public function testMin($values, $expected)
    {
        $traversable = new ArrayObject($values);

        $sum = new MinAggregation();
        $actual = $sum->aggregate($traversable);

        $this->assertEquals($expected, $actual);
    }
}
