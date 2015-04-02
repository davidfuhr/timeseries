<?php

namespace Flagbit\Timeseries\DataMapper;
use Flagbit\Timeseries\TimeseriesInterface;

/**
 * @see http://martinfowler.com/eaaCatalog/dataMapper.html
 */
interface DataMapperInterface
{
    public function insert(TimeseriesInterface $timeseries);

//    public function update(TimeseriesInterface $timeseries);

//    public function delete($name);

//    public function find($name);
}
