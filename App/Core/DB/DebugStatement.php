<?php

namespace App\Core\DB;

use PDOStatement as PDOStatementAlias;

/**
 * Class DebugStatement
 * Special class for logging SQL DB queries
 * @package App\Core
 */
class DebugStatement
{
    private PDOStatementAlias $stmt;

    /**
     * DebugStatement constructor.
     * @param $stmt
     */
    public function __construct($stmt)
    {
        $this->stmt = $stmt;
    }

    /**
     * Overloaded method for PDO::execute (SQL logging added)
     * @param $params
     * @return bool
     */
    public function execute($params)
    {
        $result = $this->stmt->execute($params);
        ob_start();
        $this->stmt->debugDumpParams();
        Connection::appendQueryLog(ob_get_clean());
        return $result;
    }

    /**
     * Call all other methods from PDOConnection as usual
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->stmt->{$name}(...$arguments);
    }
}