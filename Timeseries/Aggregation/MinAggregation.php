<?php

namespace Flagbit\Timeseries\Aggregation;

use Traversable;

class MinAggregation implements AggregationInterface
{
    /**
     * @param Traversable $timeseries
     *
     * @return scalar
     */
    public function aggregate(Traversable $timeseries)
    {
        $min = null;
        foreach ($timeseries as $value) {
            if (null === $min || $min > $value) {
                $min = $value;
            }
        }

        return $min;
    }
}
