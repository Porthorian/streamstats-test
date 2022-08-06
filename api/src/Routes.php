<?php

declare(strict_types=1);

namespace Porthorian\StreamStats;

use Slim\Interfaces\RouteCollectorProxyInterface;
use Porthorian\StreamStats\Controllers\Twitch\CallbackController;

class Routes
{
	private RouteCollectorProxyInterface $collector;

	public function __construct(RouteCollectorProxyInterface $collector)
	{
		$this->collector = $collector;
	}

	public function generateRoutes() : void
	{
		$this->twitchIntegration();
	}

	private function twitchIntegration()
	{
		$this->collector->group('/twitch', function (RouteCollectorProxyInterface $group) {
			$group->get('/callback', CallbackController::class.':login');
			$group->get('/redirect', CallbackController::class.':sendRedirect');
		});
	}
}
