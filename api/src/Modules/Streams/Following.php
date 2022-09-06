<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Streams;

use Porthorian\EntityOrm\Model\Model;

class Following extends Model
{
	protected int $FOLLOWINGID = 0;
	protected int $USERID = 0;
	protected int $STREAMID = 0;

	public function getFollowingId() : int
	{
		return $this->FOLLOWINGID;
	}

	public function setFollowingId(int $following_id) : void
	{
		$this->FOLLOWINGID = $following_id;
	}

	public function getUserId() : int
	{
		return $this->USERID;
	}

	public function setUserId(int $user_id) : void
	{
		$this->USERID = $user_id;
	}

	public function getStreamId() : int
	{
		return $this->STREAMID;
	}

	public function setStreamId(int $stream_id) : void
	{
		$this->STREAMID = $stream_id;
	}

	////
	// Interface routines
	////

	public function getPrimaryKey() : string|int
	{
		return $this->getFollowingId();
	}

	public function setPrimaryKey(string|int $pk_value) : void
	{
		$this->setFollowingId($pk_value);
	}

	public function toArray() : array
	{
		return [
			'FOLLOWINGID' => $this->getFollowingId(),
			'USERID' => $this->getUserId(),
			'STREAMID' => $this->getStreamId()
		];
	}

	public function toPublicArray() : array
	{
		return $this->toArray();
	}

	public function getEntityPath() : string
	{
		return '\Porthorian\StreamStats\Modules\Streams\FollowingEntity';
	}
}