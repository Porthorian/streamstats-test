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
	 */
	public function getTotalStreamsForEachGame(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		$game = new GameEntity();
		$output = [];
		foreach ($game->getTotalStreamsForEachGame() as $stat)
		{
			$output[] = [
				'GAMEID' => $stat['GAMEID'],
				'name' => $stat['game_name'],
				'total_streams' => $stat['total_streams']
			];
		}

		return ResponseHelper::success($response, $output);
	}

	/**
	 * Top games by viewer count for each game
	 */
	public function getTopGamesByViewerCount(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		$game = new GameEntity();
		$output = [];
		foreach ($game->getTopGamesByViewerCount() as $stat)
		{
			$output[] = [
				'GAMEID'        => $stat['GAMEID'],
				'name'          => $stat['game_name'],
				'total_viewers' => $stat['total_viewers']
			];
		}

		return ResponseHelper::success($response, $output);
	}
}
