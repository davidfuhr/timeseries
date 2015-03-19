timeseries [![Build Status](https://travis-ci.org/davidfuhr/timeseries.svg)](https://travis-ci.org/davidfuhr/timeseries)
==========

Description
-----------

A library for handling timeseries with a fixed interval.

A timeseries consists of two parts. The data and meta information. Meta
information includes

* Timeseries name: An arbitrary name for reference. It is a best practice to
  use the interval as suffix for the name (e.g. @costs\_pt15m@)
* Interval
* Timezone name (e.g. "Europe/Berlin")

Grouping and Aggregation
------------------------

Groups the timeseries by day and sums the value:

```php
$interval = new DateInterval('P1M');
$chunkOperation = new \Flagbit\Timeseries\Operation\ChunkOperation($interval);
$chunks = $chunkOperation->split($timeseries);
$sumAggregation = new \Flagbit\Timeseries\Aggregation\SumAggregation();
$aggregatedValues = [];
foreach ($chunks as $chunk) {
    $aggregatedValues[] = $sumAggregation->aggregate($chunk);
}
$timeseries = new \Flagbit\Timeseries\Timeseries($timeseries->getStart(), $interval, $aggregatedValues);
```

Avaliable Aggregations are

* MAX
* MIN
* SUM

Grouping is currently only possible by DateTimeInterval. The Chunk Interval has
to be a multiple of the timeseries' interval.

Querying and Persistence
------------------------

The Persistence Layer is still under heavy development. So please refer to the
"Future Plans" section for the conceptual thoughts.

Future Plans
------------

* It MUST be possible to save a timeseries with an arbitrary name
* The library MUST select the best way to store the data
* The library MUST store timeseries with different intervals in different
  tables.
* The library MUST provide a way to select and update a timeseries only
  partially.
* The library MUST provide a way to split a timeseries at any given point in
  time.
* The library SHOULD "snap" to the "next best" interval if the given range on
  selecting a timeframe is not aligning with the timeseries' intervals.

See [RFC2119](https://www.ietf.org/rfc/rfc2119.txt).

