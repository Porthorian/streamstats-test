<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Porthorian\StreamStats\Modules\Games\GameEntity;
use Porthorian\Utility\Json\JsonWrapper;

class GamesController
{
	public function getTotalStreamsForEachGame(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		$game = new GameEntity();
		$output = ['data' => []];
		foreach ($game->getTotalStreamsForEachGame() as $stat)
		{
			$output['data'][] = [
				'GAMEID' => $stat['GAMEID'],
				'name' => $stat['game_name'],
				'total_streams' => $stat['total_streams']
			];
		}

		$response->getBody()->write(JsonWrapper::json($output));
		return $response->withHeader('Content-type', 'application/json')->withStatus(200);
	}

	public function getTopGamesByViewerCount(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		$game = new GameEntity();
		$output = ['data' => []];
		foreach ($game->getTopGamesByViewerCount() as $stat)
		{
			$output['data'][] = [
				'GAMEID'        => $stat['GAMEID'],
				'name'          => $stat['game_name'],
				'total_viewers' => $stat['total_viewers']
			];
		}

		$response->getBody()->write(JsonWrapper::json($output));
		return $response->withHeader('Content-type', 'application/json')->withStatus(200);
	}
}
