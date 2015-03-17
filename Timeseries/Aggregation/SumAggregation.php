<?php

namespace Flagbit\Timeseries\Aggregation;

use Traversable;

class SumAggregation implements AggregationInterface
{
    /**
     * @param Traversable $timeseries
     *
     * @return scalar
     */
    public function aggregate(Traversable $timeseries)
    {
        return array_sum(iterator_to_array($timeseries));
    }
}
