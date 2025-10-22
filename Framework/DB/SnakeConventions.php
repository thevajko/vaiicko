<?php

namespace Framework\DB;

use Framework\Support\Inflect;

/**
 * Class SnakeConventions
 * Implementation of naming conventions that adhere to the snake_case format for database operations.
 *
 * This class follows these conventions:
 * - Model property names are camel-cased while database column names are converted to snake_case.
 * - Database column names are in snake_case, and model property names are converted to camelCase.
 * - Table names are pluralized from model names and converted to snake_case.
 * - The primary key column name is consistently 'id'.
 *
 * @package App\Core\DB
 */
class SnakeConventions implements IDbConvention
{
    /**
     * Returns the pluralized name of the database table in snake_case format.
     *
     * This method can be overridden in a subclass of the Model class.
     *
     * @param string $className The fully qualified class name of the model.
     * @return string The snake_case pluralized name of the corresponding database table.
     */
    public function getTableName(string $className): string
    {
        $arr = explode("\\", $className);
        $tableName = Inflect::pluralize(strtolower(end($arr)));
        return $this->toSnakeCase($tableName);
    }

    /**
     * Returns the name of the primary key for the model.
     *
     * This method can be overridden in a subclass of the Model class.
     *
     * @param string $className The fully qualified class name of the model.
     * @return string The primary key column name, which defaults to 'id'.
     */
    public function getPkColumnName(string $className): string
    {
        return 'id';
    }

    /**
     * Return foreign key name as (classname in snake case)_id
     * @param string $className Class name of a model
     * @return string
     */
    public function getFkColumn(string $className)
    {
        $arr = explode("\\", $className);
        return strtolower($this->toSnakeCase(end($arr))) . "_id";
    }

    /**
     * Converts a model property name to the corresponding database column name in snake_case format.
     *
     * @param string $propertyName The name of the model property.
     * @return string The database column name in snake_case.
     */
    public function toDbColumnName(string $propertyName): string
    {
        return $this->toSnakeCase($propertyName);
    }

    /**
     * Converts a database column name to the corresponding model property name in camelCase format.
     *
     * @param string $columnName The name of the database column.
     * @return string The model property name in camelCase.
     */
    public function toPropertyName(string $columnName): string
    {
        return $this->toCamelCase($columnName);
    }

    /**
     * Converts a string from snake_case to camelCase.
     *
     * @param string $input The input string in snake_case format.
     * @param string $separator The separator used in the input string, typically an underscore (_).
     * @return string The converted string in camelCase format.
     */
    private function toCamelCase(string $input, string $separator = '_'): string
    {
        return lcfirst(str_replace($separator, '', ucwords($input, $separator)));
    }

    /**
     * Converts a string from camelCase to snake_case.
     *
     * @param string $input The input string in camelCase format.
     * @return string The converted string in snake_case format.
     */
    private function toSnakeCase(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
}
