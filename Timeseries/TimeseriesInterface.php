<?php

namespace Flagbit\Timeseries;

use Countable;
use Traversable;

interface TimeseriesInterface extends Countable, Traversable
{
    /**
     * @return \DateTimeImmutable
     */
    public function getStart();

    /**
     * @return \DateInterval
     */
    public function getInterval();
}
