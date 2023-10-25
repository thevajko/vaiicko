<?php

/**
 * Class ClassLoader
 * Just for automated class loading by namespace
 */
class ClassLoader
{
    public static function Boot()
    {
        spl_autoload_register(function ($class_name) {
            // input param is full name of the required class
            // there must be the same dir structure as namespace to make this work correctly
            // first we must convert namespace separators to dir separators of current system
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';
            // check if file with class exists
            if (file_exists($file)) {
                // if do include it
                require $file;
            } else {
                // if not throw exception
                throw new Exception("Class {$class_name} file {$file} was not found.");
            }
        });
    }

}

// register class loading
ClassLoader::Boot();