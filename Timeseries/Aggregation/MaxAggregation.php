<?php

namespace Flagbit\Timeseries\Aggregation;

use Traversable;

class MaxAggregation implements AggregationInterface
{
    /**
     * @param Traversable $timeseries
     *
     * @return scalar
     */
    public function aggregate(Traversable $timeseries)
    {
        return max(iterator_to_array($timeseries));
    }
}
