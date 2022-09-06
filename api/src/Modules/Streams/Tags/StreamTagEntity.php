<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Streams\Tags;

use Porthorian\DBEntity\DBEntity;
use Porthorian\PDOWrapper\DBWrapper;

class StreamTagEntity extends DBEntity
{
	public function getSharedTagsFromFollowingToTop1000(int $USERID) : array
	{
		$results = DBWrapper::PResult('
			SELECT DISTINCT tags.* FROM users_following_streams
			LEFT JOIN stream_tags
				ON users_following_streams.STREAMID = stream_tags.STREAMID
			LEFT JOIN tags
				ON stream_tags.TAGID = tags.TAGID
			WHERE users_following_streams.USERID = ?
			AND users_following_streams.STREAMID IN (
				SELECT * FROM (
					SELECT STREAMID FROM streams
					ORDER BY viewers DESC
					LIMIT 1000
				) AS temp
			)
		', [$USERID]);

		$output = [];
		foreach ($results as $result)
		{
			$tag = new Tag();
			$tag->setModelProperties($result);
			$tag->setInitializedFlag(true);
			$output[] = $tag;
		}

		return $output;
	}

	////
	// Interface routines
	////

	public function getModelPath() : string
	{
		return '\Porthorian\StreamStats\Modules\Streams\Tags\StreamTag';
	}

	public function getCollectionTable() : string
	{
		return 'stream_tags';
	}

	public function getCollectionPrimaryKey() : string
	{
		return 'STREAMTAGID';
	}
}