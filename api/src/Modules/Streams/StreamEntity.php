<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Streams;

use Porthorian\DBEntity\DBEntity;
use Porthorian\PDOWrapper\DBWrapper;

class StreamEntity extends DBEntity
{
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
