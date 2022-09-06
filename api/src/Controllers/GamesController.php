<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Porthorian\StreamStats\Modules\Games\GameEntity;
use Porthorian\StreamStats\Util\ResponseHelper;

class GamesController
{
	/**
	 * Total number of streams for each game
	 * aggregate via query
	 */
	public function getTotalStreamsForEachGame(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		return ResponseHelper::success($response, (new GameEntity())->getTotalStreamsForEachGame());
	}

	/**
	 * Top games by viewer count for each game
	 * aggregate via query
	 */
	public function getTopGamesByViewerCount(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		return ResponseHelper::success($response, (new GameEntity())->getTopGamesByViewerCount());
	}
}
