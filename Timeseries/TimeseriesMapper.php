<?php

namespace Flagbit\Timeseries;

use DateTimeInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Statement;
use Guzzle\Iterator\ChunkedIterator;

class TimeseriesMapper
{
    const QUARTERS_PER_DAY = 96;

    const QUERY_DELETE_TIMESERIES_DATA = 'DELETE FROM ts_data WHERE series_id = :series_id;';

    const QUERY_SELECT_TIMESERIES_DATA = 'SELECT * FROM ts_data WHERE series_id = :series_id ORDER BY start_timestamp';

    const QUERY_SELECT_TIMESERIES_DATA_IN_RANGE = 'SELECT * FROM ts_data WHERE series_id = :series_id
        AND start_timestamp >= :start AND start_timestamp <= :end';

    /**
     * @var Connection
     */
    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function insert(Timeseries $timeseries, $seriesId)
    {
        $start = $timeseries->getStart();

        // we store in utc so create our row date
        $rowDate = new \DateTime('@'.$start->format('U'));
        $rowDate->setTime(0, 0, 0);

        $dayInterval = new \DateInterval('P1D');

        // compute offset to real start to insert dummy NULL values
        $dummyValues = new \ArrayIterator(array_fill(
            0, iterator_count(new \DatePeriod($rowDate, new \DateInterval('PT15M'), $start)), 'NULL'
        ));

        $valueIterator = new \AppendIterator();
        $valueIterator->append($dummyValues);
        $valueIterator->append(new \IteratorIterator($timeseries));

        $rowIterator = new ChunkedIterator($valueIterator, self::QUARTERS_PER_DAY);
        $rows = [];
        foreach ($rowIterator as $rowValues) {
            $rows[] = $seriesId.','.$rowDate->format('U').','.implode(',', array_pad($rowValues, self::QUARTERS_PER_DAY, 'NULL'));
            $rowDate->add($dayInterval);
        }

        try {
            $this->db->setAutoCommit(false);
            $this->db->beginTransaction();
            $this->db->executeQuery(self::QUERY_DELETE_TIMESERIES_DATA, ['series_id' => $seriesId]);
            $insertSql = 'INSERT INTO ts_data VALUES ('.implode('),(', $rows).');';
            $this->db->exec($insertSql);
            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        } finally {
            $this->db->setAutoCommit(true);
        }
    }

    public function select($seriesId)
    {
        $selectStmt = $this->db->prepare(self::QUERY_SELECT_TIMESERIES_DATA);
        $selectStmt->execute(['series_id' => $seriesId]);

        return $this->fetchStatementToTimeseries($selectStmt);
    }

    public function selectRange($seriesId, DateTimeInterface $start, DateTimeInterface $end)
    {
        $startRowDate = new \DateTime('@'.$start->format('U'));
        $startRowDate->setTime(0, 0, 0);

        $endRowDate = new \DateTime('@'.$end->format('U'));
        $endRowDate->setTime(0, 0, 0);

        $selectStmt = $this->db->prepare(self::QUERY_SELECT_TIMESERIES_DATA_IN_RANGE);
        $selectStmt->execute(['series_id' => $seriesId, 'start' => $startRowDate->format('U'), 'end' => $endRowDate->format('U')]);

//        return $this->fetchStatementToTimeseries($selectStmt, $start, $end);
    }

    private function fetchStatementToTimeseries(Statement $statement)
    {
        // first row
        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        if (false === $row) {
            throw new \RuntimeException('Timeseries is empty');
        }

        // set row date ahead for NULL values
        $rowDate = new \DateTime('@'.$row['start_timestamp']);
        $quarterInterval = new \DateInterval('PT15M');
        $interval = 0;
        while (null === $row['interval_'.$interval++]) {
            $rowDate->add($quarterInterval);
        }

        $timeseriesValues = [];
        do {
            while ($interval < self::QUARTERS_PER_DAY) {
                $value = $row['interval_'.$interval++];
                if (null === $value) {
                    break 2;
                }
                $timeseriesValues[] = $value;
            }

            $interval = 0;
        } while ($row = $statement->fetch(\PDO::FETCH_ASSOC));

        return new Timeseries(new \DateTimeImmutable('@'.$rowDate->format('U')), new \DateInterval('PT15M'), $timeseriesValues);
    }
}
