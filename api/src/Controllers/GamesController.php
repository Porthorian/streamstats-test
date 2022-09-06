<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class GamesController
{
	public function getTotalNumberStreamsForEachGame(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		return $response;
	}

	public function getTopGamesByViewerCount(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		return $response;
	}
}
