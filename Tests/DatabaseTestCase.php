<?php

namespace Flagbit\Timeseries\Tests;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use PDO;
use PHPUnit_Extensions_Database_DB_IDatabaseConnection;

abstract class DatabaseTestCase extends \PHPUnit_Extensions_Database_TestCase
{
    /**
     * only instantiate pdo once for test clean-up/fixture load
     */
    static private $pdo = null;

    static private $dbal = null;

    /**
     * only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
     */
    private $conn = null;

    /**
     * Returns the test database connection.
     *
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    final public function getConnection()
    {
        if ($this->conn === null) {
            if (null === self::$pdo) {
                self::$pdo = new PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
            }
            if (null === self::$dbal) {
                self::$dbal = DriverManager::getConnection(['pdo' => self::$pdo]);
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }

        return $this->conn;
    }

    /**
     * @return Connection
     */
    final protected function getDbal()
    {
        return self::$dbal;
    }
}
