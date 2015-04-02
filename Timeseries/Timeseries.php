<?php

namespace Flagbit\Timeseries;

use ArrayIterator;
use DateInterval;
use DateTimeInterface;
use IteratorAggregate;
use Traversable;

class Timeseries implements IteratorAggregate, TimeseriesInterface
{
    /**
     * @var DateTimeInterface
     */
    private $start;

    /**
     * @var DateInterval
     */
    private $interval;

    /**
     * @var array
     */
    private $values;

    /**
     * @var string
     */
    private $name;

    public function __construct(DateTimeInterface $start, DateInterval $interval, array $values = [], $name = '')
    {
        $this->start = $start;
        $this->interval = $interval;
        $this->values = $values;
        $this->name = $name;
    }

    public function __clone()
    {
        $this->start = clone $this->start;
    }

    /**
     * @return DateTimeInterface
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return DateInterval
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator.
     *
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     *
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     *                     <b>Traversable</b>
     */
    public function getIterator()
    {
        return new ArrayIterator($this->values);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object.
     *
     * @link http://php.net/manual/en/countable.count.php
     *
     * @return int The custom count as an integer.
     *             </p>
     *             <p>
     *             The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->values);
    }
}
