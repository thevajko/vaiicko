<?php

// Require the class loader to enable automatic loading of classes
require __DIR__ . '/../Framework/ClassLoader.php';

use Framework\Core\App;

try {
    // Create an instance of the App class
    $app = new App();

    // Run the application
    $app->run();
} catch (Exception $e) {
    // Handle any exceptions that occur during the application run
    die('<pre>An error occurred: ' . $e->getMessage() . PHP_EOL . PHP_EOL . $e->getTraceAsString() . '</pre>');
}
