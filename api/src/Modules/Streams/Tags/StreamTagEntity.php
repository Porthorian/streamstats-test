<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Streams\Tags;

use Porthorian\DBEntity\DBEntity;

class StreamTagEntity extends DBEntity
{
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