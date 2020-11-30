<?php

namespace App\Core\DB;

use App\Config\Configuration;
use PDO;
use PDOException;

class Connection
{
    private $db;
    private static $instance;

    private static $log = [];

    public function __construct($db)
    {
        $this->db = $db;
    }

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
     * Creates a new connection to DB, if connection already exists, returns the existing one
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
        return $this->db->{$name}(...$arguments);
    }

    /**
     * @return array
     */
    public static function getQueryLog(): array
    {
        return self::$log;
    }
}