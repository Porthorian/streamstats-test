<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Streams;

use Porthorian\DBEntity\DBEntity;

class FollowingEntity extends DBEntity
{
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