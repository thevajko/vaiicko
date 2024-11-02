<?php

namespace App\Core;

use App\Config\Configuration;
use App\Core\DB\Connection;
use App\Core\DB\IDbConvention;
use App\Core\Http\Request;
use Exception;
use PDO;
use PDOException;

/**
 * Class Model
 *
 * Abstract base class that serves as a foundation for all models within the application. This class provides basic
 * Create, Read, Update, and Delete (CRUD) operations and defines a standard structure for model interactions
 * with the database.
 *
 * @package App\Core\Storage
 */
abstract class Model implements \JsonSerializable
{
    private static array $dbColumns = []; // Cache for database column names
    private static IDbConvention $dbConventions; // Instance for database naming conventions
    private mixed $_dbId = null; // Store the primary key value for the model

    /**
     * Retrieves the table name associated with the model class.
     *
     * This method can be overridden in a subclass to provide a custom table name.
     *
     * @return string The name of the database table corresponding to the model.
     */
    protected static function getTableName(): string
    {
        return self::getConventions()->getTableName(get_called_class());
    }

    /**
     * Retrieves the default primary key column name for the model.
     *
     * This method can be overridden in a subclass to specify a custom primary key name.
     *
     * @return string The name of the primary key column in the database table.
     */
    protected static function getPkColumnName(): string
    {
        return self::getConventions()->getPkColumnName(get_called_class());
    }

    /**
     * Retrieves the mapping of model property names to database column names.
     *
     * This method can be overridden in a subclass to provide custom mappings.
     *
     * @return array An associative array where keys are model property names and values are DB column names.
     */
    protected static function getColumnsMap(): array
    {
        return [];
    }

    /**
     * Populates the model's properties with values from the incoming request.
     *
     * This method matches property names with corresponding request parameters.
     *
     * @param Request $request The incoming HTTP request containing parameters.
     * @return void
     */
    public function setFromRequest(Request $request): void
    {
        $data = $request->isPost() ? $request->post() : $request->get();
        foreach ($data as $key => $value) {
            if (property_exists(get_class($this), $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Retrieves all models from the database.
     *
     * This method accepts optional parameters for filtering, ordering, limiting, and offsetting results.
     *
     * @param string|null $whereClause Optional WHERE clause for filtering results.
     * @param array $whereParams Optional parameters for the WHERE clause.
     * @param string|null $orderBy Optional ORDER BY clause for sorting results.
     * @param int|null $limit Optional limit on the number of results.
     * @param int|null $offset Optional offset for paginating results.
     * @return static[] An array of model instances retrieved from the database.
     * @throws Exception If there is an error executing the SQL query.
     */
    public static function getAll(
        ?string $whereClause = null,
        array   $whereParams = [],
        ?string $orderBy = null,
        ?int    $limit = null,
        ?int    $offset = null
    ): array
    {
        try {
            $sql = "SELECT " . self::getDBColumnNamesList() . " FROM `" . static::getTableName() . "`";
            if ($whereClause !== null) {
                $sql .= " WHERE $whereClause";
            }
            if ($orderBy !== null) {
                $sql .= " ORDER BY $orderBy";
            }
            if ($limit !== null) {
                $sql .= " LIMIT $limit";
            }
            if ($offset !== null) {
                $sql .= " OFFSET $offset";
            }

            $stmt = Connection::getInstance()->prepare($sql);
            $stmt->execute($whereParams);
            $models = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, static::class);
            foreach ($models as $model) {
                $model->_dbId = $model->getIdValue();
            }
            return $models;
        } catch (PDOException $exception) {
            throw new Exception('Query failed: ' . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Retrieves a single model instance from the database by its primary key.
     *
     * @param mixed $id The primary key value of the desired model.
     * @return static|null The model instance, or null if not found.
     * @throws Exception If there is an error executing the SQL query.
     */
    public static function getOne(mixed $id): ?static
    {
        if ($id === null) {
            return null;
        }

        try {
            $sql = "SELECT " . self::getDBColumnNamesList() . " FROM `" . static::getTableName() . "` WHERE `" .
                static::getPkColumnName() . "`=?";
            $stmt = Connection::getInstance()->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, static::class);
            $stmt->execute([$id]);
            $model = $stmt->fetch() ?: null;
            if ($model !== null) {
                $model->_dbId = $model->getIdValue();
            }
            return $model;
        } catch (PDOException $exception) {
            throw new Exception('Query failed: ' . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Counts the number of models in the database.
     *
     * @param string|null $whereClause Optional WHERE clause for filtering results.
     * @param array $whereParams Optional parameters for the WHERE clause.
     * @return int The count of models matching the criteria.
     * @throws Exception If there is an error executing the SQL query.
     */
    public static function getCount(?string $whereClause = null, array $whereParams = []): int
    {
        try {
            $sql = "SELECT COUNT(*) FROM `" . static::getTableName() . "`";
            if ($whereClause !== null) {
                $sql .= " WHERE $whereClause";
            }

            $stmt = Connection::getInstance()->prepare($sql);
            $stmt->execute($whereParams);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $exception) {
            throw new Exception('Query failed: ' . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Saves the current model instance to the database.
     *
     * If the model has a primary key value set, it updates the existing record;
     * otherwise, it inserts a new record.
     *
     * @return void
     * @throws Exception If there is an error executing the SQL query.
     */
    public function save(): void
    {
        try {
            $data = array_fill_keys(static::getDbColumns(), null);
            foreach ($data as $key => &$item) {
                $item = $this->{static::toPropertyName($key)};
            }
            // Insert new record
            if ($this->_dbId === null) {
                $arrColumns = array_map(fn($item) => (':' . $item), array_keys($data));
                $columns = '`' . implode('`,`', array_keys($data)) . "`";
                $params = implode(',', $arrColumns);
                $sql = "INSERT INTO `" . static::getTableName() . "` ($columns) VALUES ($params)";
                $stmt = Connection::getInstance()->prepare($sql);
                $stmt->execute($data);

                $pkPropertyName = static::toPropertyName(static::getPkColumnName());
                if (!isset($this->{$pkPropertyName})) {
                    $this->{$pkPropertyName} = Connection::getInstance()->lastInsertId();
                    $this->_dbId = $this->{$pkPropertyName};
                }
                // Update existing record
            } else {
                $arrColumns = array_map(fn($item) => ("`" . $item . '`=:' . $item), array_keys($data));
                $columns = implode(',', $arrColumns);
                $sql = "UPDATE `" . static::getTableName() . "` SET $columns WHERE `" . static::getPkColumnName() .
                    "`=:__pk";
                $stmt = Connection::getInstance()->prepare($sql);
                $data["__pk"] = $this->_dbId;
                $stmt->execute($data);
            }
        } catch (PDOException $exception) {
            throw new Exception('Query failed: ' . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Deletes the current model instance from the database.
     *
     * If the model does not exist, an exception is thrown.
     *
     * @throws Exception If the model does not exist or if there is an error executing the SQL query.
     */
    public function delete(): void
    {
        if ($this->getIdValue() == null) {
            return;
        }
        try {
            $sql = "DELETE FROM `" . static::getTableName() . "` WHERE `" . static::getPkColumnName() . "`=?";
            $stmt = Connection::getInstance()->prepare($sql);
            $stmt->execute([$this->getIdValue()]);
            if ($stmt->rowCount() == 0) {
                throw new Exception('Model not found!');
            }
        } catch (PDOException $exception) {
            throw new Exception('Query failed: ' . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Executes a raw SQL query and returns the result.
     *
     * This method is designed to run custom SQL queries that may not fit the standard CRUD operations provided
     * by the model. It prepares and executes the query securely using parameter binding to prevent SQL injection.
     *
     * @param string $sql The raw SQL query to be executed.
     * @param array $bindParams An associative array of parameters to bind to the SQL query.
     *                          Keys should match the named placeholders in the SQL statement.
     * @return array The result set as an array of associative arrays, with each associative array
     *               representing a row from the result. If no rows are returned, an empty array is returned.
     * @throws Exception If there is an error executing the SQL query, an Exception is thrown with
     *                   the PDOException's message for easier debugging.
     */
    public static function executeRawSQL(string $sql, array $bindParams = []): array
    {
        try {
            $stmt = Connection::getInstance()->prepare($sql);
            $stmt->execute($bindParams);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            throw new Exception('Query failed: ' . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Default implementation of the JSON serialize method. Converts the model's properties to an array for JSON
     * serialization, excluding the internal `_dbId` property.
     *
     * @return array An associative array of the model's properties for JSON output.
     */
    public function jsonSerialize(): array
    {
        $properties = get_object_vars($this);
        unset($properties["_dbId"]); // Remove internal object ID
        return $properties;
    }

    /**
     * Retrieves an array of column names from the associated database table. Caches the results to optimize performance
     * on subsequent calls.
     *
     * @return array An array of column names from the model's database table.
     * @throws Exception If the query fails due to a database error.
     */
    private static function getDbColumns(): array
    {
        if (isset(self::$dbColumns[static::class])) {
            return self::$dbColumns[static::class];
        }
        try {
            $sql = "DESCRIBE " . static::getTableName();
            $stmt = Connection::getInstance()->prepare($sql);
            $stmt->execute([]);
            self::$dbColumns[static::class] = array_column($stmt->fetchAll(), 'Field');
            return self::$dbColumns[static::class];
        } catch (PDOException $exception) {
            throw new Exception('Query failed: ' . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Retrieves the value of the model's primary key. This is used internally to identify the model instance
     * in the database.
     *
     * @return mixed The value of the primary key property for the model.
     */
    private function getIdValue(): mixed
    {
        $pk = static::getPkColumnName();
        return $this->{self::toPropertyName($pk)};
    }

    /**
     * Generates a list of database column names formatted for a SELECT SQL clause. Maps the database column names
     * to their corresponding model property names.
     *
     * @return string A comma-separated string of formatted column names for SQL SELECT.
     * @throws Exception If the query fails due to a database error.
     */
    private static function getDBColumnNamesList(): string
    {
        $dbColumns = [];
        foreach (static::getDbColumns() as $columnName) {
            $name = self::toPropertyName($columnName);
            if ($name != $columnName) {
                $dbColumns[] = "`$columnName` AS {$name}";
            } else {
                $dbColumns[] = $columnName;
            }
        }
        return implode(', ', $dbColumns);
    }

    /**
     * Converts a database column name to the corresponding model property name. This method takes into account any
     * user-defined mappings if applicable.
     *
     * @param string $columnName The name of the database column to convert.
     * @return string The corresponding model property name.
     */
    private static function toPropertyName(string $columnName): string
    {
        $customMapping = static::getColumnsMap();
        if (isset($customMapping[$columnName])) {
            return $customMapping[$columnName];
        } else {
            return static::getConventions()->toPropertyName($columnName);
        }
    }

    /**
     * Retrieves the instance of the naming conventions used in the database. This method ensures that the conventions
     * are instantiated only once, promoting efficient use of resources.
     *
     * @return IDbConvention An instance of the naming conventions used for the database.
     */
    private static function getConventions(): IDbConvention
    {
        if (!isset(self::$dbConventions)) {
            self::$dbConventions = new (Configuration::DB_CONVENTIONS_CLASS)();
        }
        return self::$dbConventions;
    }
}
