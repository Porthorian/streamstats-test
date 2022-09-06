<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Streams\Tags;

use Porthorian\DBEntity\DBEntity;

class TagEntity extends DBEntity
{
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