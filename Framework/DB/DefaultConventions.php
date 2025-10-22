<?php

namespace Framework\DB;

use Framework\Support\Inflect;

/**
 * Class DefaultConventions
 *
 * Implements default naming conventions for database tables and columns. This class defines how model names are
 * translated into database table names and how model properties are mapped to database columns.
 *
 * The conventions are:
 * - Table names are pluralized from model names.
 * - Column names remain unchanged.
 * - The primary key column is assumed to be named 'id'.
 *
 * This class can be extended to customize naming conventions as needed.
 */
class DefaultConventions implements IDbConvention
{
    /**
     * Returns the pluralized name of the database table based on the model class name.
     *
     * This method can be overridden in a descendant of the Model class to provide custom table naming conventions.
     *
     * @param string $className The class name of the model for which to derive the table name.
     * @return string The pluralized table name.
     */
    public function getTableName(string $className): string
    {
        $arr = explode("\\", $className); // Split the namespace to get the class name
        return Inflect::pluralize(strtolower(end($arr))); // Pluralize and return the table name
    }

    /**
     * Returns the name of the primary key for the given model class.
     *
     * This method always returns 'id', but it can be overridden in a descendant of the Model class if a different
     * primary key naming convention is used.
     *
     * @param string $className The class name of the model.
     * @return string The name of the primary key column.
     */
    public function getPkColumnName(string $className): string
    {
        return 'id'; // Default primary key column name
    }

    /**
     * Return foreign key name as (classname)Id
     * @param string $className Class name of a model
     * @return string
     */
    public function getFkColumn(string $className)
    {
        $arr = explode("\\", $className);
        return lcfirst(end($arr)) . "Id";
    }

    /**
     * Returns the database column name corresponding to a model property.
     *
     * In this implementation, the column name is assumed to be the same as the property name, which means no
     * transformation is applied.
     *
     * @param string $propertyName The name of the model property.
     * @return string The corresponding database column name.
     */
    public function toDbColumnName(string $propertyName): string
    {
        return $propertyName; // Column name matches property name
    }

    /**
     * Returns the model property name corresponding to a database column name.
     *
     * In this implementation, the property name is assumed to be the same as the database column name, meaning no
     * transformation is applied.
     *
     * @param string $columnName The name of the database column.
     * @return string The corresponding model property name.
     */
    public function toPropertyName(string $columnName): string
    {
        return $columnName; // Property name matches column name
    }
}
