<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Streams;

use InvalidArgumentException;
use Porthorian\DBEntity\DBEntity;
use Porthorian\PDOWrapper\DBWrapper;
use Porthorian\StreamStats\Util\DataFormat;

class StreamEntity extends DBEntity
{
	public function getStreamsByTwitchStreamId(array $twitch_stream_ids) : array
	{
		if (empty($twitch_stream_ids))
		{
			return [];
		}

		$results = DBWrapper::PResult('
			SELECT * FROM streams WHERE TWITCHSTREAMID IN ('.DataFormat::getCommaListFromArray($twitch_stream_ids).')
		');

		$output = [];
		foreach ($results as $result)
		{
			$stream = new Stream();
			$stream->setModelProperties($result);
			$stream->setInitializedFlag(true);
			$output[$result['TWITCHSTREAMID']] = $stream;
		}
		return $output;
	}

	public function getMedianViewersForAllStreams() : int
	{
		$results = DBWrapper::PResult('
			SELECT DISTINCT viewers FROM streams ORDER BY viewers DESC;
		');
		$viewers = [];
		foreach ($results as $result)
		{
			$viewers[] = $result['viewers'];
		}

		$length = count($viewers);
		/**
		 * Binary search the median number
		 */
		if ($length % 2 === 0)
		{
			$middle_index = floor($length / 2);

			return (int)floor(($viewers[$middle_index] + $viewers[$middle_index - 1]) / 2);
		}

		return $viewers[floor($length / 2)];
	}

	public function getTop100StreamsByViewer(string $order) : array
	{
		$order = strtoupper($order);
		if (!in_array($order, ['ASC', 'DESC']))
		{
			throw new InvalidArgumentException('Invalid order given. Only ASC and DESC allowed.');
		}

		$output = [];
		foreach (DBWrapper::PResult('SELECT * FROM streams ORDER BY viewers '.$order.' LIMIT 100') as $result)
		{
			$stream = new Stream();
			$stream->setModelProperties($result);
			$stream->setInitializedFlag(true);
			$output[] = $stream;
		}

		return $output;
	}

	public function getTotalStreamsByStartTimeRounded() : array
	{
		$results = DBWrapper::PResult('
			SELECT DATE_ADD(
				DATE_FORMAT(date_started, "%Y-%m-%d %H:00:00"), INTERVAL IF(
					MINUTE(date_started) < 30, 0, 1) HOUR
				) AS rounded_start, COUNT(*) AS total_streams
			FROM streams
			GROUP BY rounded_start
			ORDER BY rounded_start DESC
		');

		$output = [];
		foreach ($results as $result)
		{
			$output[] = [
				'date_started'  => $result['rounded_start'],
				'total_streams' => $result['total_streams']
			];
		}
		return $output;
	}

	public function getTop1000Streams() : array
	{
		$results = DBWrapper::PResult('
			SELECT * FROM streams
			ORDER BY viewers DESC
			LIMIT 1000
		');
		$output = [];
		foreach ($results as $result)
		{
			$stream = new Stream();
			$stream->setModelProperties($result);
			$stream->setInitializedFlag(true);
			$output[] = $stream;
		}
		return $output;
	}

	////
	// Interface routines
	////

	public function getModelPath() : string
	{
		return '\Porthorian\StreamStats\Modules\Streams\Stream';
	}

	public function getCollectionTable() : string
	{
		return 'streams';
	}

	public function getCollectionPrimaryKey() : string
	{
		return 'STREAMID';
	}
}
