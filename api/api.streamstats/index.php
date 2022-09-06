<?php

declare(strict_types=1);

require __DIR__.'/../includes/init_script.php';

use Slim\Factory\AppFactory;
use Porthorian\StreamStats\Routes;
use Porthorian\StreamStats\Session;
use Porthorian\Utility\Key\Generator;
use Porthorian\StreamStats\Env\Environment;
use Psr\Http\Message\ServerRequestInterface;
use Porthorian\StreamStats\Util\ResponseHelper;

Session::configure();
Session::start();
if (!Session::has('csrf_token'))
{
	Session::set('csrf_token', Generator::generateToken(45));
}

$app = AppFactory::create();

$middleware = $app->addErrorMiddleware(Environment::isDevEnv(), true, true);
$middleware->setDefaultErrorHandler(function (
	ServerRequestInterface $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails) use ($app) {
	$error = [];
	$http_code = 500;
	if ($exception->getCode() >= 400 && $exception->getCode() < 500)
	{
		$http_code = $exception->getCode();
		$error['error'] = $exception->getMessage();
	}

	if ($displayErrorDetails)
	{
		$error['error'] = $exception->getMessage();
		$error['trace'] = $exception->getTrace();
	}

	return ResponseHelper::error($app->getResponseFactory()->createResponse(), $http_code, $error);
});

$routes = new Routes($app);
$routes->generateRoutes();

$app->run();

