<?php

namespace Flagbit\Timeseries\Operation;

use Flagbit\Timeseries\Timeseries;
use Flagbit\Timeseries\TimeseriesInterface;

class ChunkOperation implements SplitOperationInterface
{
    /**
     * @var \DateInterval
     */
    private $interval;

    public function __construct(\DateInterval $interval)
    {
        $this->interval = $interval;
    }

    /**
     * @param TimeseriesInterface $timeseries
     *
     * @return \Flagbit\Timeseries\TimeseriesInterface[]
     */
    public function split(TimeseriesInterface $timeseries)
    {
        $timeseriesIterator = new \IteratorIterator($timeseries);
        $timeseriesIterator->rewind();

        $chunkedTimeseries = [];

        while ($timeseriesIterator->valid()) {
            $chunkStart = new \DateTimeImmutable(
                $timeseries->getStart()->format('Y-m-d H:i:s'), $timeseries->getStart()->getTimezone()
            );

            $chunkEnd = $chunkStart->add($this->interval);
            $timeseriesPos = $chunkStart;

            $chunkValues = [];
            while ($timeseriesIterator->valid() && $timeseriesPos < $chunkEnd) {
                $chunkValues[] = $timeseriesIterator->current();

                $timeseriesPos = $timeseriesPos->add($timeseries->getInterval());
                $timeseriesIterator->next();
            }
            $chunkedTimeseries[] = new Timeseries($chunkStart, $this->interval, $chunkValues);
        }

        return $chunkedTimeseries;
    }
}
