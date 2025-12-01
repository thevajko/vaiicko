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
    header('Content-Type: text/plain; charset=utf-8');

    if (getenv('APP_ENV') === 'development' || getenv('APP_DEBUG') === 'true') {
        die("An error occurred: {$e->getMessage()}" . PHP_EOL . PHP_EOL . $e->getTraceAsString());
    } else {
        die("An error occurred. Please contact the administrator.");
    }
}
