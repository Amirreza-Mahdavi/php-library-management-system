<?php

require __DIR__ . '/../vendor/autoload.php';

use LMS\CLI\Application;

try {
    $application = new Application();
    $application->run();
}
catch (Throwable $e) {
    fwrite(STDERR, "Error: {$e->getMessage()}" . PHP_EOL);
    exit(1);
}