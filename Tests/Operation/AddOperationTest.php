<?php

namespace Flagbit\Timeseries\Tests\Operation;

use DateInterval;
use DateTimeImmutable;
use Flagbit\Timeseries\Operation\AddOperation;
use Flagbit\Timeseries\Timeseries;

class AddOperationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \UnexpectedValueException
     */
    public function testCannotAddWithDifferentStart()
    {
        $interval = new DateInterval('PT15M');

        $one = new Timeseries(new DateTimeImmutable('2014-01-01'), $interval, []);
        $two = new Timeseries(new DateTimeImmutable('2014-02-01'), $interval, []);

        $add = new AddOperation();
        $add->merge($one, $two);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testCannotAddWithDifferentInterval()
    {
        $start = new DateTimeImmutable('now');

        $one = new Timeseries($start, new DateInterval('PT30M'), []);
        $two = new Timeseries($start, new DateInterval('PT1H'), []);

        $add = new AddOperation();
        $add->merge($one, $two);
    }

    public function provideTestAddWithSameStartAndSameLength()
    {
        return [
            [[1, 5, 4, 3, 2], [4, 0, 6, 2, 8], [5, 5, 10, 5, 10]],
            [['0.1', 51231], ['0.001', '0.001'], ['0.101', '51231.001']],
            [[0, 0, 1, 0], [1, 0, 0, 0], [1, 0, 1, 0]],
            [[3, null], [null, 4], [3, 4]],
        ];
    }

    /**
     * @dataProvider provideTestAddWithSameStartAndSameLength
     */
    public function testAddWithSameStartAndSameLength($aValues, $bValues, $expected)
    {
        $start = new DateTimeImmutable('now');
        $interval = new DateInterval('PT15M');

        $a = new Timeseries(
            $start,
            $interval,
            $aValues
        );
        $b = new Timeseries(
            $start,
            $interval,
            $bValues
        );

        $add = new AddOperation();
        bcscale(3);
        $start = new DateTimeImmutable('now');
        $c = $add->merge($a, $b);

        $this->assertEquals($expected, iterator_to_array($c));
    }

    public function provideTestAddWithDifferentLength()
    {
        return [
            [[1, 5, 4, 3, 2], [4, 0, 6], [5, 5, 10, 3, 2]],
            [['0.1', 51231, 64], ['0.001', '0.001'], ['0.101', '51231.001', 64]],
            [[], [3, 64, 2], [3, 64, 2]],
        ];
    }

    /**
     * @dataProvider provideTestAddWithDifferentLength$timeseries
     */
    public function testAddWithDifferentLength($aValues, $bValues, $expected)
    {
        $start = new DateTimeImmutable('now');
        $interval = new DateInterval('PT15M');
        $a = new Timeseries(
            $start,
            $interval,
            $aValues
        );
        $b = new Timeseries(
            $start,
            $interval,
            $bValues
        );

        $add = new AddOperation();
        bcscale(3);
        $c = $add->merge($a, $b);

        $this->assertEquals($expected, iterator_to_array($c));
    }

    public function provideTestAddMoreThanTwo()
    {
        return [
            [[[1, 2, 3], [4, 5, 6], [7, 8, 9]], [12, 15, 18]],
        ];
    }

    /**
     * @dataProvider provideTestAddMoreThanTwo
     */
    public function testAddMoreThanTwo($allSeries, $expected)
    {
        $start = new DateTimeImmutable('now');
        $interval = new DateInterval('PT15M');

        $timeseries = [];
        foreach ($allSeries as $oneSeries) {
            $timeseries[] = new Timeseries(
                $start,
                $interval,
                $oneSeries
            );
        }

        $add = new AddOperation();
        $actual = call_user_func_array(array($add, 'merge'), $timeseries);

        $this->assertEquals($expected, iterator_to_array($actual));
    }
}
