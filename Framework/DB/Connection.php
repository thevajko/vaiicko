<?php

namespace Framework\DB;

use App\Configuration;
use Exception;
use PDO;
use PDOException;

/**
 * Class Connection
 *
 * The Connection class manages the database connection for a MySQL (or MariaDB) database. It implements the Singleton
 * pattern to ensure that only one instance of the database connection is created and used throughout the application,
 * promoting resource efficiency and consistency.
 *
 * This class provides methods to establish the database connection, prepare SQL statements, log executed queries for
 * debugging purposes, and access the underlying PDO object for executing raw database operations.
 *
 * @package App\Core\DB
 */
class Connection
{
    private static ?Connection $instance = null;
    private static array $log = [];
    private PDO $db;

    /**
     * Connection constructor.
     * Initializes the database connection with a PDO instance.
     *
     * @param PDO $db The PDO instance representing the database connection.
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Retrieves the singleton instance of the Connection class.Creates a new database connection if one does not
     * already exist.
     *
     * @return Connection The instance of the Connection class.
     * @throws Exception If the connection fails to be established.
     */
    public static function getInstance(): Connection
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
            throw new Exception('Connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Appends a new SQL command to the query log for debugging purposes.
     *
     * @param string $query The SQL query to log.
     * @return void
     */
    public static function appendQueryLog(string $query): void
    {
        self::$log[] = $query;
    }

    /**
     * Retrieves the log of executed SQL queries.
     *
     * @return array An array containing the logged SQL queries.
     */
    public static function getQueryLog(): array
    {
        return self::$log;
    }

    /**
     * Prepares an SQL statement for execution, returning a DebugStatement object. This method provides enhanced
     * debugging by wrapping the PDO statement preparation.
     *
     * @param string $sql The SQL query to prepare.
     * @return DebugStatement The prepared statement wrapped for debugging.
     * @throws Exception If the statement preparation fails.
     */
    public function prepare(string $sql): DebugStatement
    {
        try {
            return new DebugStatement($this->db->prepare($sql));
        } catch (PDOException $e) {
            throw new Exception('Prepare failed: ' . $e->getMessage());
        }
    }

    /**
     * Magic method that allows calls to undefined methods on the Connection class. This forwards the method call
     * to the underlying PDO instance.
     *
     * @param string $name The name of the method to call.
     * @param array $arguments The arguments to pass to the method.
     * @return mixed The return value from the invoked PDO method.
     */
    public function __call(string $name, array $arguments): mixed
    {
        return $this->db->{$name}(...$arguments);
    }
}
