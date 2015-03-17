<?php

namespace Flagbit\Timeseries\Aggregation;

use Traversable;

interface AggregationInterface
{
    /**
     * @param Traversable $timeseries
     *
     * @return scalar
     */
    public function aggregate(Traversable $timeseries);
}
