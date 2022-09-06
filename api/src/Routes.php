<?php

declare(strict_types=1);

namespace Porthorian\StreamStats;

use Slim\Interfaces\RouteCollectorProxyInterface;
use Porthorian\StreamStats\Controllers\Twitch\CallbackController;
use Porthorian\StreamStats\Controllers\GamesController;
use Porthorian\StreamStats\Controllers\StreamsController;
use Porthorian\StreamStats\Controllers\AuthController;
use Porthorian\StreamStats\Middleware\Auth;

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
		$this->authRoutes();
		$this->gamesRoutes();
		$this->streamsRoutes();
	}

	private function twitchIntegration() : void
	{
		$this->collector->group('/twitch', function (RouteCollectorProxyInterface $group) {
			$group->get('/callback', CallbackController::class.':login');
			$group->get('/redirect', CallbackController::class.':sendRedirect');
		});
	}

	private function authRoutes() : void
	{
		$this->collector->get('/auth/logout', AuthController::class.':logout')->add(new Auth());
		$this->collector->get('/user', AuthController::class.':getLoggedInUser')->add(new Auth());
	}

	/**
	 * Total number of streams for each game
	 * Top games by viewer count for each game
	 */
	private function gamesRoutes() : void
	{
		$this->collector->group('/games', function (RouteCollectorProxyInterface $group) {
			$group->get('/total', GamesController::class.':getTotalStreamsForEachGame');
			$group->get('/top', GamesController::class.':getTopGamesByViewerCount');
		})->add(new Auth());
	}

	/**
	 * Median number of viewers for all streams
	 * List of top 100 streams by viewer count that can be sorted asc & desc
	 * Total number of streams by their start time (rounded to the nearest hour)
	 * Which of the top 1000 streams is the logged in user following?
	 * How many viewers does the lowest viewer count stream that the logged in user is following need to gain in order to make it into the top 1000?
	 * Which tags are shared between the user followed streams and the top 1000 streams? Also make sure to translate the tags to their respective name?
	 */
	private function streamsRoutes() : void
	{
		$this->collector->group('/streams', function (RouteCollectorProxyInterface $group) {
			$group->get('/median', StreamsController::class.':getMedianOfViewers');
			$group->get('/top100', StreamsController::class.':getTop100StreamsByViewerCount');
			$group->get('/total_start', StreamsController::class.':getTotalStreamsByStartTime');
			$group->get('/top1000_following', StreamsController::class.':getTop1000StreamsFollowingByUser');
			$group->get('/lowest_following_calc', StreamsController::class.':getCalcLowestViewerToMakeTop1000');
			$group->get('/tags_shared', StreamsController::class.':getTagsSharedBetweenFollowedStreams');
		})->add(new Auth());
	}
}
