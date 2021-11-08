<?php

namespace App\Core\DB;

use App\Config\Configuration;
use PDO;
use PDOException;

/**
 * Class Connection
 * Class for connecting to database
 * @package App\Core\DB
 */
class Connection
{
    private static $instance;
    private static $log = [];
    private $db;

    /**
     * Connection constructor
     * @param $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Creates a new connection to DB, if connection already exists, returns the existing one (singleton)
     * @return Connection
     * @throws \Exception
     */
    public static function connect()
    {
        try {
            if (self::$instance == null) {
                $db = new PDO('mysql:dbname=' . Configuration::DB_NAME . ';host=' . Configuration::DB_HOST, Configuration::DB_USER, Configuration::DB_PASS);
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
     * Prepare SQL command
     * @return \PDOStatement | DebugStatement
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
     * Appends query to log of all queries (for one action) for debugging purposes
     * @param $query
     */
    public static function appendQueryLog($query)
    {
        self::$log[] = $query;
    }

    /**
     * Call all other methods from PDOConnection
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        try {
            return $this->db->{$name}(...$arguments);
        } catch (PDOException $e) {
            throw new \Exception('DB command failed: ' . $e->getMessage());
        }
    }

    /**
     * Return query debug log
     * @return array
     */
    public static function getQueryLog(): array
    {
        return self::$log;
    }
}