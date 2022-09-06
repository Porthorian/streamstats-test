<?php

declare(strict_types=1);

require __DIR__.'/../includes/init_script.php';

use Slim\Factory\AppFactory;
use Porthorian\StreamStats\Routes;

$app = AppFactory::create();
$routes = new Routes($app);
$routes->generateRoutes();

$app->run();

