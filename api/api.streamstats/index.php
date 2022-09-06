<?php

declare(strict_types=1);

require __DIR__.'/../includes/init_script.php';

use Slim\Factory\AppFactory;
use Porthorian\StreamStats\Routes;
use Porthorian\StreamStats\Session;
use Porthorian\Utility\Key\Generator;

$app = AppFactory::create();
$routes = new Routes($app);
$routes->generateRoutes();

Session::configure();
Session::start();
if (!Session::has('csrf_token'))
{
	Session::set('csrf_token', Generator::generateToken(45));
}

$app->run();

