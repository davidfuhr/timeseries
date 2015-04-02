<?php

namespace Flagbit\Timeseries\Tests\DataMapper;

use Doctrine\DBAL\Connection;
use Flagbit\Timeseries\DataMapper\ShardDataMapper;
use Flagbit\Timeseries\Tests\DatabaseTestCase;
use Flagbit\Timeseries\Timeseries;
use PHPUnit_Extensions_Database_DataSet_IDataSet;

class ShardDataMapperTest extends DatabaseTestCase
{
    /**
     * Returns the test dataset.
     *
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    protected function getDataSet()
    {
        return $this->createMySQLXMLDataSet(__DIR__ . '/_files/shard-seed.xml');
    }

    public function provideInsert()
    {
        return [
            [
                new \DateTimeImmutable('2015-02-01 00:00:00', new \DateTimeZone('UTC')),
                new \DateInterval('PT15M'),
                [1, 1, 2, 4, 5, 7, 8.9],
                'timeseries_utc',
                'insert-utc-seed.xml',
            ],
            [
                new \DateTimeImmutable('2015-02-01 00:00:00', new \DateTimeZone('Europe/Berlin')),
                new \DateInterval('PT15M'),
                [1, 1, 2, 4, 5, 7, 8.9],
                'timeseries_europe_berlin',
                'insert-berlin-seed.xml',
            ]
        ];
    }

    /**
     * @dataProvider provideInsert
     */
    public function testInsert($start, $interval, $values, $name, $expectedFixtureFile)
    {
        $timeseries = new Timeseries($start, $interval, $values, $name);

        $shardMapper = new ShardDataMapper($this->getDbal());
        $shardMapper->insert($timeseries);

        $expectedDataSet = $this->createMySQLXMLDataSet(__DIR__ . '/_files/' . $expectedFixtureFile);

        $this->assertDataSetsEqual(
            $expectedDataSet,
            $this->getConnection()->createDataSet(['timeseries', 'timeseries_data_shard'])
        );
    }
}
