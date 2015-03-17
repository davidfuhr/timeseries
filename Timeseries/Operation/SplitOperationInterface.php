<?php

namespace Flagbit\Timeseries\Operation;

use Flagbit\Timeseries\TimeseriesInterface;

interface SplitOperationInterface
{
    /**
     * @param TimeseriesInterface $timeseries
     *
     * @return \Flagbit\Timeseries\TimeseriesInterface[]
     */
    public function split(TimeseriesInterface $timeseries);
}
