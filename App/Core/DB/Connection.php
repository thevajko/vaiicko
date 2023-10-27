<?php

namespace App\Core\DB;

use App\Config\Configuration;
use PDO;
use PDOException;

/**
 * Class Connection
 * Class for Mysql DB (MariaDB) connection
 * @package App\Core\DB
 */
class Connection
{
    private static $instance;
    private static $log = [];
    private $db;

    /**
     * Constructor for DB connection
     * @param $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Create a connection to DB or return existing one, if exists
     * @return Connection
     * @throws \Exception
     */
    public static function connect()
    {
        try {
            if (self::$instance == null) {
                $db = new PDO(
                    'mysql:dbname=' . Configuration::DB_NAME . ';host=' . Configuration::DB_HOST,
                    Configuration::DB_USER,
                    Configuration::DB_PASS
                );
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
                self::$instance = new self($db);
            }
            return self::$instance;
        } catch (PDOException $e) {
            throw new \Exception('Connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Append a new SQL command to query log
     * @param $query
     * @return void
     */
    public static function appendQueryLog($query)
    {
        self::$log[] = $query;
    }

    /**
     * Get query log
     * @return array
     */
    public static function getQueryLog(): array
    {
        return self::$log;
    }

    /**
     * Overridden version of prepare method (for debugging purposes)
     * @param $sql
     * @return DebugStatement
     * @throws \Exception
     */
    public function prepare($sql)
    {
        try {
            return new DebugStatement($this->db->prepare($sql));
        } catch (PDOException $e) {
            throw new \Exception('Prepare failed: ' . $e->getMessage());
        }
    }

    /**
     * Call all other methods from PDOConnection
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->db->{$name}(...$arguments);
    }
}
