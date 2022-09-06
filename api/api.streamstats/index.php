<?php

declare(strict_types=1);

require __DIR__.'/../includes/init_script.php';

use Slim\Factory\AppFactory;
use Porthorian\StreamStats\Routes;
use Porthorian\StreamStats\Session;
use Porthorian\Utility\Key\Generator;

Session::configure();
Session::start();
if (!Session::has('csrf_token'))
{
	Session::set('csrf_token', Generator::generateToken(45));
}

$app = AppFactory::create();
// TODO ADD error middleware

$routes = new Routes($app);
$routes->generateRoutes();

$app->run();

