<?php

namespace Framework\DB;

/**
 * Interface IDbConvention
 *
 * Defines a set of methods that outline the conventions for mapping database names to model properties.
 * Implementations of this interface allow for flexibility in defining how database tables and columns correspond
 * to the properties of models in the application.
 */
interface IDbConvention
{
    /**
     * Get the name of the database table associated with a model. This method can be overridden in subclasses
     * of the Model class to provide custom table naming.
     *
     * @param string $className The fully qualified class name of the model.
     * @return string The name of the corresponding database table.
     */
    public function getTableName(string $className): string;

    /**
     * Get the name of the primary key column for a model. This method can be overridden in subclasses of the Model
     * class to specify a custom primary key.
     *
     * @param string $className The fully qualified class name of the model.
     * @return string The name of the primary key column.
     */
    public function getPkColumnName(string $className): string;

    /**
     * Returns the name of the foreign key for model class
     * @param string $className Class name of the model
     * @return string
     */
    public function getFkColumn(string $className);

    /**
     * Convert a model property name to its corresponding database column name. This method should maintain consistency
     * between model properties and their respective database columns.
     *
     * @param string $propertyName The name of the model property.
     * @return string The name of the corresponding database column.
     */
    public function toDbColumnName(string $propertyName): string;

    /**
     * Convert a database column name to its corresponding model property name. This method is used to ensure that
     * database columns can be accessed as properties of a model.
     *
     * @param string $columnName The name of the database column.
     * @return string The name of the corresponding model property.
     */
    public function toPropertyName(string $columnName): string;
}
