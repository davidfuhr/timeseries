<?php

namespace Flagbit\Timeseries\Operation;

use Flagbit\Timeseries\TimeseriesInterface;

interface MergeOperationInterface
{
    /**
     * @param TimeseriesInterface ...$timeseries
     *
     * @return TimeseriesInterface
     */
    public function merge();
}
