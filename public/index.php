<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../laravel_core/storage/framework/maintenance.php')) {
    require $maintenance;
} elseif (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
if (file_exists(__DIR__.'/../laravel_core/vendor/autoload.php')) {
    require __DIR__.'/../laravel_core/vendor/autoload.php';
} else {
    require __DIR__.'/../vendor/autoload.php';
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
if (file_exists(__DIR__.'/../laravel_core/bootstrap/app.php')) {
    $app = require_once __DIR__.'/../laravel_core/bootstrap/app.php';
} else {
    $app = require_once __DIR__.'/../bootstrap/app.php';
}

// Automatically set the public path to whatever directory this index.php is in (e.g. public_html)
$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());
