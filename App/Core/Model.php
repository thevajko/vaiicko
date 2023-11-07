<?php

namespace App\Core;

use App\Core\DB\Connection;
use App\Helpers\Inflect;
use PDO;
use PDOException;

/**
 * Class Model
 * Abstract class serving as a simple model example, predecessor of all models
 * Allows basic CRUD operations
 * @package App\Core\Storage
 */
abstract class Model implements \JsonSerializable
{
    private static ?Connection $connection = null;
    private static ?array $dbColumns = null;
    private mixed $_dbId = null;

    /**
     * Return an array of models from DB
     * @param string $whereClause Additional where Statement
     * @param array $whereParams Parameters for where
     * @param string|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return static[]
     * @throws \Exception
     */
    public static function getAll(
        ?string $whereClause = null,
        array $whereParams = [],
        ?string $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): array {
        self::connect();
        try {
            $sql = "SELECT * FROM `" . static::getTableName() . "`";
            if ($whereClause != null) {
                $sql .= " WHERE $whereClause";
            }
            if ($orderBy != null) {
                $sql .= " ORDER BY $orderBy";
            }
            if ($limit != null) {
                $sql .= " LIMIT $limit";
            }
            if ($offset != null) {
                $sql .= " OFFSET $offset";
            }

            $stmt = self::$connection->prepare($sql);
            $stmt->execute($whereParams);
            $models = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, static::class);
            foreach ($models as $model) {
                $model->_dbId = $model->{static::getPkColumnName()};
            }
            return $models;
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Connect to DB
     * @return null
     * @throws \Exception
     */
    private static function connect(): void
    {
        self::$connection = Connection::connect();
    }

    /**
     * Get table name from model class name
     * @return string
     */
    public static function getTableName(): string
    {
        $arr = explode("\\", get_called_class());
        return Inflect::pluralize(strtolower(end($arr)));
    }

    /**
     * Return default primary key column name
     * @return string
     */
    public static function getPkColumnName(): string
    {
        return 'id';
    }

    /**
     * Gets one model by primary key
     * @param $id
     * @return static|null
     * @throws \Exception
     */
    public static function getOne($id): ?static
    {
        if ($id == null) {
            return null;
        }

        self::connect();
        try {
            $sql = "SELECT * FROM `" . static::getTableName() . "` WHERE `" . static::getPkColumnName() . "`=?";
            $stmt = self::$connection->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, static::class);
            $stmt->execute([$id]);
            $model = $stmt->fetch() ?: null;
            if ($model != null) {
                $model->_dbId = $model->{static::getPkColumnName()};
            }
            return $model;
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Return DB connection, ready for custom developer use
     * @return null
     */
    public static function getConnection()
    {
        return self::$connection;
    }

    /**
     * Save the current model to DB (if model id is set, update it, else create a new model)
     * @return void
     * @throws \Exception
     */
    public function save(): void
    {
        self::connect();
        try {
            $data = array_fill_keys(static::getDbColumns(), null);
            foreach ($data as $key => &$item) {
                $item = isset($this->$key) ? $this->$key : null;
            }
            if ($this->_dbId == null) {
                $arrColumns = array_map(fn($item) => (':' . $item), array_keys($data));
                $columns = '`' . implode('`,`', array_keys($data)) . "`";
                $params = implode(',', $arrColumns);
                $sql = "INSERT INTO `" . static::getTableName() . "` ($columns) VALUES ($params)";
                $stmt = self::$connection->prepare($sql);
                $stmt->execute($data);
                if (!isset($this->{static::getPkColumnName()})) {
                    $this->{static::getPkColumnName()} = self::$connection->lastInsertId();
                    $this->_dbId = $this->{static::getPkColumnName()};
                }
            } else {
                $arrColumns = array_map(fn($item) => ("`" . $item . '`=:' . $item), array_keys($data));
                $columns = implode(',', $arrColumns);
                $sql = "UPDATE `" . static::getTableName() . "` SET $columns WHERE `" . static::getPkColumnName() .
                    "`=:__pk";
                $stmt = self::$connection->prepare($sql);
                $data["__pk"] = $this->_dbId;
                $stmt->execute($data);
            }
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Get array of column names from the associated model table
     * @return array
     * @throws \Exception
     */
    public static function getDbColumns(): array
    {
        if (self::$dbColumns != null) {
            return self::$dbColumns;
        }
        self::connect();
        try {
            $sql = "DESCRIBE " . static::getTableName();
            $stmt = self::$connection->prepare($sql);
            $stmt->execute([]);
            self::$dbColumns = array_column($stmt->fetchAll(), 'Field');
            return self::$dbColumns;
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Delete current model from DB
     * @throws \Exception If model not exists, throw an exception
     */
    public function delete()
    {
        if ($this->{static::getPkColumnName()} == null) {
            return;
        }
        self::connect();
        try {
            $sql = "DELETE FROM `" . static::getTableName() . "` WHERE `" . static::getPkColumnName() . "`=?";
            $stmt = self::$connection->prepare($sql);
            $stmt->execute([$this->{static::getPkColumnName()}]);
            if ($stmt->rowCount() == 0) {
                throw new \Exception('Model not found!');
            }
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Default implementation of JSON serialize method
     * @return array
     */
    public function jsonSerialize(): array
    {
        $properties = get_object_vars($this);
        unset($properties["_dbId"]); //Remove internal object ID
        return $properties;
    }

    /**
     * Check if DB contains a column, which doesn't exist in model class as an attribute
     * @param string $name
     * @param $value
     * @return void
     * @throws \Exception
     */
    public function __set(string $name, $value): void
    {
        throw new \Exception("Attribute `$name` doesn't exist in the model " . get_called_class() . ".");
    }
}
