<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Streams\Tags;

use Porthorian\PDOWrapper\DBWrapper;
use Porthorian\DBEntity\DBEntity;
use Porthorian\StreamStats\Util\DataFormat;

class TagEntity extends DBEntity
{
	public function getTagsByTwitchId(array $twitch_tag_ids) : array
	{
		if (empty($twitch_tag_ids))
		{
			return [];
		}

		$results = DBWrapper::PResult('
			SELECT * FROM tags WHERE TWITCHTAGID IN ('.DataFormat::getCommaListFromArray($twitch_tag_ids).')
		');

		$output = [];
		foreach ($results as $result)
		{
			$tag = new Tag();
			$tag->setModelProperties($result);
			$tag->setInitializedFlag(true);
			$output[$result['TWITCHTAGID']] = $tag;
		}
		return $output;
	}
	////
	// Interface routines
	////

	public function getModelPath() : string
	{
		return '\Porthorian\StreamStats\Modules\Streams\Tags\Tag';
	}

	public function getCollectionTable() : string
	{
		return 'tags';
	}

	public function getCollectionPrimaryKey() : string
	{
		return 'TAGID';
	}
}