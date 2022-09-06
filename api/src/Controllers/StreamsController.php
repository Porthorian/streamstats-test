<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Porthorian\StreamStats\Modules\Streams\StreamEntity;
use Porthorian\StreamStats\Util\ResponseHelper;

class StreamsController
{
	/**
	 * Median number of viewers for all streams
	 */
	public function getMedianOfViewers(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		$median = (new StreamEntity())->getMedianViewersForAllStreams();

		return ResponseHelper::success($response, ['median' => $median]);
	}

	/**
	 * List of top 100 streams by viewer count that can be sorted asc & desc
	 */
	public function getTop100StreamsByViewerCount(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		$params = $request->getQueryParams();
		$order = $params['order'] ?? 'desc';
		if (!in_array($order, ['desc', 'asc']))
		{
			return ResponseHelper::errorMessage($response, 'Invalid order given. Only asc and desc allowed.');
		}
		return $response;
	}

	/**
	 * Total number of streams by their start time (rounded to the nearest hour)
	 */
	public function getTotalStreamsByStartTime(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		return $response;
	}

	/**
	 * Which of the top 1000 streams is the logged in user following?
	 */
	public function getTop1000StreamsFollowingByUser(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		return $response;
	}

	/**
	 * How many viewers does the lowest viewer count stream that the logged in user is following need to gain in order to make it into the top 1000?
	 */
	public function getCalcLowestViewerToMakeTop1000(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		return $response;
	}

	/**
	 * Which tags are shared between the user followed streams and the top 1000 streams? Also make sure to translate the tags to their respective name?
	 */
	public function getTagsSharedBetweenFollowedStreams(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		return $response;
	}
}
