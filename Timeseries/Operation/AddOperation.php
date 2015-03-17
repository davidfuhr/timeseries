<?php

namespace Flagbit\Timeseries\Operation;

use Flagbit\Timeseries\Timeseries;
use Flagbit\Timeseries\TimeseriesInterface;

class AddOperation implements MergeOperationInterface
{
    /**
     * @param TimeseriesInterface ...$timeseries
     *
     * @return TimeseriesInterface
     */
    public function merge()
    {
        $timeseries = func_get_args();

        $iterators = [];
        $resultStart = null;
        $resultInterval = null;
        foreach ($timeseries as $oneTimeseries) {
            if (null === $resultStart) {
                $resultStart = $oneTimeseries->getStart();
            }
            if ($resultStart != $oneTimeseries->getStart()) {
                throw new \UnexpectedValueException('Cannot add timeseries with different start.');
            }

            if (null === $resultInterval) {
                $resultInterval = $oneTimeseries->getInterval();
            }
            if ($resultInterval->format('%y-%m-%d-%h-%i-%s') != $oneTimeseries->getInterval()->format('%y-%m-%d-%h-%i-%s')) {
                throw new \UnexpectedValueException('Cannot add timeseries with different interval.');
            }

            $iterator = new \IteratorIterator($oneTimeseries);
            $iterator->rewind();
            $iterators[] = $iterator;
        }

        $timeseriesValues = [];
        while (0 !== $operandsCount = count($operands = $this->collectOperands($iterators))) {
            $value = array_pop($operands);
            for ($i = 1; $i < $operandsCount; ++$i) {
                $operand = array_pop($operands);
                $value = bcadd($value, $operand);
            }
            $timeseriesValues[] = $value;
        }

        return new Timeseries(clone $resultStart, clone $resultInterval, $timeseriesValues);
    }

    /**
     * @param \Iterator[] $iterators
     *
     * @return array
     */
    private function collectOperands($iterators)
    {
        $operands = [];
        foreach ($iterators as $iterator) {
            if ($iterator->valid()) {
                $operands[] = $iterator->current();
                $iterator->next();
            }
        }

        return $operands;
    }
}
