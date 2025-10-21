<?php

namespace Framework\DB;

use PDOStatement as PDOStatementAlias;

/**
 * Class DebugStatement
 *
 * The DebugStatement class enhances the standard PDOStatement by adding logging functionality for SQL queries executed
 * against a database. It allows for capturing and storing the executed SQL commands and their parameters, which is
 * invaluable for debugging and monitoring database interactions.
 *
 * @package App\Core
 */
class DebugStatement
{
    private PDOStatementAlias $stmt;

    /**
     * DebugStatement constructor.
     *
     * Initializes a new instance of the DebugStatement class by wrapping an existing PDOStatement object. This allows
     * for enhanced functionality, such as logging executed queries.
     *
     * @param PDOStatementAlias $stmt The PDOStatement instance to wrap.
     */
    public function __construct($stmt)
    {
        $this->stmt = $stmt;
    }

    /**
     * Executes the prepared statement with the provided parameters and logs the query.
     *
     * This method overrides the standard execute method of PDOStatement to add logging capabilities. It captures the
     * SQL command and its parameters for debugging purposes before executing the statement.
     *
     * @param array|null $params Optional parameters to bind to the statement.
     * @return bool Returns true on success or false on failure.
     */
    public function execute(?array $params = null): bool
    {
        $result = $this->stmt->execute($params);
        ob_start(); // Start output buffering to capture debug output
        $this->stmt->debugDumpParams(); // Dumps the parameters for logging
        Connection::appendQueryLog(ob_get_clean()); // Log the captured output
        return $result;
    }

    /**
     * Magic method to dynamically call methods on the underlying PDOStatement.
     *
     * This method proxies calls to any undefined methods directly to the wrapped PDOStatement instance, allowing for
     * full access to PDO's methods.
     *
     * @param string $name The name of the method to call on the PDOStatement.
     * @param array $arguments The arguments to pass to the method.
     * @return mixed The return value from the invoked method on the PDOStatement.
     */
    public function __call(string $name, array $arguments): mixed
    {
        return $this->stmt->{$name}(...$arguments);
    }
}
