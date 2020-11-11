<?php

namespace App\Core;

use App\App;
use PDO;
use PDOException;

/**
 * Class Model
 * Abstract class serving as a simple model example, predecessor of all models
 * Allows basic CRUD operations
 * @package App\Core\Storage
 */
abstract class Model
{
    private static $db = null;
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
     * Creates a new connection to DB, if connection already exists, returns the existing one
     */
    private static function connect()
    {
        $config = App::getConfig();
        try {
            if (self::$db == null) {
                self::$db = new PDO('mysql:dbname=' . $config::DB_NAME . ';host=' . $config::DB_HOST, $config::DB_USER, $config::DB_PASS);
            }
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Gets DB connection for additional model methods
     * @return null
     */
    protected static function getConnection()
    {
        self::connect();
        return self::$db;
    }

    /**
     * Return an array of models from DB
     * @return array
     */
    static public function getAll()
    {
        self::connect();
        $dbModels = self::$db->query("SELECT * FROM " . self::getTableName())->fetchAll();
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
    }

    /**
     * Gets one model by primary key
     * @param $id
     * @throws \Exception
     */
    public function getOne($id)
    {
        self::connect();
        $sql = "SELECT * FROM " . self::getTableName() . " WHERE id=$id";
        $stmt = self::$db->prepare($sql);
        $stmt->execute([$id]);
        $model = $stmt->fetch();
        if ($model) {
            $data = array_fill_keys(self::getDbColumns(), null);
            foreach ($data as $key => $item) {
                $this->$key = $model[$key];
            }
        } else {
            throw new \Exception('Model not found!');
        }
    }

    /**
     * Saves the current model to DB (if model id is set, updates it, else creates a new model)
     * @return mixed
     */
    public function save()
    {
        self::connect();
        $data = array_fill_keys(self::getDbColumns(), null);
        foreach ($data as $key => &$item) {
            $item = $this->$key;
        }
        if ($data[self::$pkColumn] == null) {
            $arrColumns = array_map(fn($item) => (':' . $item), array_keys($data));
            $columns = implode(',', array_keys($data));
            $params = implode(',', $arrColumns);
            $sql = "INSERT INTO " . self::getTableName() . " ($columns) VALUES ($params)";
            self::$db->prepare($sql)->execute($data);
            return self::$db->lastInsertId();
        } else {
            $arrColumns = array_map(fn($item) => ($item . '=:' . $item), array_keys($data));
            $columns = implode(',', $arrColumns);
            $sql = "UPDATE " . self::getTableName() . " SET $columns WHERE id=:" . self::$pkColumn;
            self::$db->prepare($sql)->execute($data);
            return $data[self::$pkColumn];
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
        $sql = "DELETE FROM " . self::getTableName() . " WHERE id=?";
        $stmt = self::$db->prepare($sql);
        $stmt->execute([$this->{self::$pkColumn}]);
        if ($stmt->rowCount() == 0) {
            throw new \Exception('Model not found!');
        }
    }
}