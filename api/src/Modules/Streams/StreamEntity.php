<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Streams;

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
