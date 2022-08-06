<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use Porthorian\StreamStats\Routes;

require __DIR__.'/../vendor/autoload.php';

$app = AppFactory::create();
$routes = new Routes($app);
$routes->generateRoutes();

$app->run();

