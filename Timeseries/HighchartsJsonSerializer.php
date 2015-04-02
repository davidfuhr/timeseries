<?php

namespace Flagbit\Timeseries;

use DateTime;

class HighchartsJsonSerializer implements \JsonSerializable
{
    /**
     * @var Timeseries
     */
    private $timeseries;

    public function __construct(Timeseries $timeseries)
    {
        $this->timeseries = $timeseries;
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     *
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *       which is a value of any type other than a resource.
     */
    function jsonSerialize()
    {
        $start = new DateTime('@' . $this->timeseries->getStart()->format('U'));
        $start->add($this->timeseries->getInterval());
        $intervalInSeconds = $start->format('U') - $this->timeseries->getStart()->format('U');

        $series = [];

        $series['type'] = 'line';
        $series['step'] = 'left';
        $series['pointInterval'] = $intervalInSeconds * 1000;
        $series['pointStart'] = $this->timeseries->getStart()->format('U') * 1000;
        $series['data'] = array_map('floatval', iterator_to_array($this->timeseries));

        return (object) $series;
    }
}
