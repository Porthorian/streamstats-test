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