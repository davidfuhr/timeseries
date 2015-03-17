<?php

namespace Flagbit\Timeseries\Tests\Operation;

use Flagbit\Timeseries\Operation\ChunkOperation;
use Flagbit\Timeseries\Timeseries;
use PHPUnit_Framework_TestCase;

class ChunkOperationTest extends PHPUnit_Framework_TestCase
{
    public function testChunkByDay()
    {
        $values = array_pad([], 24, 1);
        $values = array_pad($values, 48, 2);
        $values = array_pad($values, 72, 3);
        $values = array_pad($values, 96, 4);

        $timeseries = new Timeseries(
            new \DateTime('2014-01-01 00:00:00'),
            new \DateInterval('PT1H'),
            $values
        );

        $chunkOperation = new ChunkOperation(new \DateInterval('P1D'));
        $parts = $chunkOperation->split($timeseries);

        $this->assertCount(4, $parts);
        foreach ($parts as $part) {
            $this->assertInstanceOf('Flagbit\\Timeseries\\Timeseries', $part);
            $this->assertCount(24, $part);
        }
    }
}
