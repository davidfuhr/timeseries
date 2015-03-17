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
        $max = null;
        foreach ($timeseries as $value) {
            if (null === $max || $max < $value) {
                $max = $value;
            }
        }

        return $max;
    }
}
