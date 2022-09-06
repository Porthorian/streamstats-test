<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Porthorian\StreamStats\Modules\Streams\StreamEntity;
use Porthorian\Utility\Json\JsonWrapper;

class StreamsController
{
	public function getMedianOfViewers(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		$median = (new StreamEntity())->getMedianViewersForAllStreams();

		$response->getBody()->write(JsonWrapper::json(['data' => ['median' => $median]]));
		return $response->withHeader('Content-type', 'application/json')->withStatus(200);
	}
}
