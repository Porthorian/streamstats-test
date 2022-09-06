<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Porthorian\StreamStats\Modules\Streams\StreamEntity;
use Porthorian\StreamStats\Modules\Streams\FollowingEntity;
use Porthorian\StreamStats\Modules\Streams\Tags\StreamTagEntity;
use Porthorian\StreamStats\Util\ResponseHelper;
use Porthorian\StreamStats\Session;

class StreamsController
{
	/**
	 * Median number of viewers for all streams
	 * aggregated via application layer.
	 */
	public function getMedianOfViewers(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		return ResponseHelper::success($response, ['median' => (new StreamEntity())->getMedianViewersForAllStreams()]);
	}

	/**
	 * List of top 100 streams by viewer count that can be sorted asc & desc
	 * aggregate via query
	 */
	public function getTop100StreamsByViewerCount(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		$params = $request->getQueryParams();

		return ResponseHelper::success($response, array_map(function ($stream) {
			return $stream->toPublicArray();
		}, (new StreamEntity())->getTop100StreamsByViewer($params['order'] ?? 'DESC')));
	}

	/**
	 * Total number of streams by their start time (rounded to the nearest hour)
	 * aggregate via query
	 */
	public function getTotalStreamsByStartTime(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		return ResponseHelper::success($response, (new StreamEntity())->getTotalStreamsByStartTimeRounded());
	}

	/**
	 * Which of the top 1000 streams is the logged in user following?
	 * aggregate via query
	 */
	public function getTop1000StreamsFollowingByUser(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		return ResponseHelper::success($response, array_map(function ($stream) {
			return $stream->toPublicArray();
		}, (new FollowingEntity())->getTop1000IsUserFollowing(Session::get('user_id_logged_in'))));
	}

	/**
	 * How many viewers does the lowest viewer count stream that the logged in user is following need to gain in order to make it into the top 1000?
	 * aggregate via application
	 */
	public function getCalcLowestViewerToMakeTop1000(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		list($stream, $viewers_needed) = (new FollowingEntity())->getLowestViewerCountToMakeToTop1000(Session::get('user_id_logged_in'));
		return ResponseHelper::success($response, ['stream' => $stream->toPublicArray(), 'viewers_needed' => $viewers_needed]);
	}

	/**
	 * Which tags are shared between the user followed streams and the top 1000 streams? Also make sure to translate the tags to their respective name?
	 * aggregate via query
	 */
	public function getTagsSharedBetweenFollowedStreams(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		return ResponseHelper::success($response, array_map(function ($tag) {
			return $tag->toPublicArray();
		},(new StreamTagEntity())->getSharedTagsFromFollowingToTop1000(Session::get('user_id_logged_in'))));
	}
}
