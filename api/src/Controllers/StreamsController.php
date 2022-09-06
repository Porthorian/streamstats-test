<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Porthorian\StreamStats\Modules\Streams\StreamEntity;

class StreamsController
{
	public function getMedianOfViewers(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		$entity = new StreamEntity();
		echo "<pre>";
		var_dump($entity->getMedianViewersForAllStreams());
		echo "</pre>";
		return $response;
	}
}
