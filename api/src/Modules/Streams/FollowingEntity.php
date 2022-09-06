<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Streams;

use Porthorian\DBEntity\DBEntity;
use Porthorian\PDOWrapper\DBWrapper;

class FollowingEntity extends DBEntity
{
	public function getTop1000IsUserFollowing(int $user_id) : array
	{
		$results = DBWrapper::PResult('
			SELECT streams.* FROM users_following_streams
			LEFT JOIN streams
				ON users_following_streams.STREAMID = streams.STREAMID
			WHERE users_following_streams.USERID = ?
			AND users_following_streams.STREAMID IN (
				SELECT * FROM (
					SELECT STREAMID FROM streams
					ORDER BY viewers DESC
					LIMIT 1000
				) AS temp
			)
		', [$user_id]);

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

	public function getLowestViewerCountToMakeToTop1000(int $user_id) : array
	{
		$streams = (new StreamEntity())->getTop1000Streams();
		$last_stream = $streams[count($streams) - 1];
		unset($streams);

		$result = DBWrapper::PSingle('
			SELECT streams.* FROM users_following_streams
			LEFT JOIN streams
				ON streams.STREAMID = users_following_streams.STREAMID
			ORDER BY streams.viewers ASC
			LIMIT 1
		');

		$stream = new Stream();
		$stream->setModelProperties($result);
		$stream->setInitializedFlag(true);
		$number = $last_stream->getViewers() - ($result['viewers'] ?? 0);
		// Pretty good taste for this you.
		// You following everything in the top 1000.
		if ($number < 0)
		{
			return [$stream, 0];
		}

		return [$stream, $number];
	}

	////
	// Interface routines
	////

	public function getModelPath() : string
	{
		return '\Porthorian\StreamStats\Modules\Streams\Following';
	}

	public function getCollectionTable() : string
	{
		return 'users_following_streams';
	}

	public function getCollectionPrimaryKey() : string
	{
		return 'FOLLOWINGID';
	}
}