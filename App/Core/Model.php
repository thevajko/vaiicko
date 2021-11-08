<?php

namespace App\Core;

use App\Core\DB\Connection;
use PDOException;

/**
 * Class Model
 * Abstract class serving as a simple model example, predecessor of all models
 * Allows basic CRUD operations
 * @package App\Core
 */
abstract class Model implements \JsonSerializable
{
    private static $connection = null;
    private static $pkColumn = 'id';

    abstract static public function setDbColumns();

    abstract static public function setTableName();

    /**
     * Gets a db columns from a model
     * @return mixed
     */
    private static function getDbColumns()
    {
        return static::setDbColumns();
    }

    /**
     * Reads the table name from a model
     * @return mixed
     */
    private static function getTableName()
    {
        return static::setTableName();
    }

    /**
     * Gets DB connection for other model methods
     * @return null
     * @throws \Exception
     */
    private static function connect()
    {
        self::$connection = Connection::connect();
    }

    /**
     * Return an array of models from DB
     * @param string $whereClause Additional where Statement
     * @param array $whereParams Parameters for where
     * @return static[]
     * @throws \Exception
     */
    static public function getAll(string $whereClause = '', array $whereParams = [])
    {
        self::connect();
        try {
            $sql = "SELECT * FROM " . self::getTableName() . ($whereClause=='' ? '' : " WHERE $whereClause");

            $stmt = self::$connection->prepare($sql);
            $stmt->execute($whereParams);

            $dbModels = $stmt->fetchAll();
            $models = [];
            foreach ($dbModels as $model) {
                $tmpModel = new static();
                $data = array_fill_keys(self::getDbColumns(), null);
                foreach ($data as $key => $item) {
                    $tmpModel->$key = $model[$key];
                }
                $models[] = $tmpModel;
            }
            return $models;
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * Gets one model by primary key
     * @param $id
     * @throws \Exception
     */
    static public function getOne($id)
    {
        if ($id == null) return null;

        self::connect();
        try {
            $sql = "SELECT * FROM " . self::getTableName() . " WHERE " . self::$pkColumn . "=?";
            $stmt = self::$connection->prepare($sql);
            $stmt->execute([$id]);
            $model = $stmt->fetch();
            if ($model) {
                $data = array_fill_keys(self::getDbColumns(), null);
                $tmpModel = new static();
                foreach ($data as $key => $item) {
                    $tmpModel->$key = $model[$key];
                }
                return $tmpModel;
            } else {
                throw new \Exception('Record not found!');
            }
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * Saves the current model to DB (if model id is set, updates it, else creates a new model)
     * @return mixed
     */
    public function save()
    {
        self::connect();
        try {
            $data = array_fill_keys(self::getDbColumns(), null);
            foreach ($data as $key => &$item) {
                $item = $this->$key;
            }
            if ($data[self::$pkColumn] == null) {
                $arrColumns = array_map(fn($item) => (':' . $item), array_keys($data));
                $columns = implode(',', array_keys($data));
                $params = implode(',', $arrColumns);
                $sql = "INSERT INTO " . self::getTableName() . " ($columns) VALUES ($params)";
                $stmt = self::$connection->prepare($sql);
                $stmt->execute($data);
                return self::$connection->lastInsertId();
            } else {
                $arrColumns = array_map(fn($item) => ($item . '=:' . $item), array_keys($data));
                $columns = implode(',', $arrColumns);
                $sql = "UPDATE " . self::getTableName() . " SET $columns WHERE id=:" . self::$pkColumn;
                $stmt = self::$connection->prepare($sql);
                $stmt->execute($data);
                return $data[self::$pkColumn];
            }
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * Deletes current model from DB
     * @throws \Exception If model not exists, throw an exception
     */
    public function delete()
    {
        if ($this->{self::$pkColumn} == null) {
            return;
        }
        self::connect();
        try {
            $sql = "DELETE FROM " . self::getTableName() . " WHERE id=?";
            $stmt = self::$connection->prepare($sql);
            $stmt->execute([$this->{self::$pkColumn}]);
            if ($stmt->rowCount() == 0) {
                throw new \Exception('Model not found!');
            }
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * Returns the connection to database
     * @return null
     */
    public static function getConnection()
    {
        return self::$connection;
    }

    /**
     * Default implementation of JSON serialize method
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}