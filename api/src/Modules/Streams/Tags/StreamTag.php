<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Streams\Tags;

use Porthorian\EntityOrm\Model\Model;

class StreamTag extends Model
{
	protected int $STREAMTAGID = 0;
	protected int $TAGID = 0;
	protected int $STREAMID = 0;

	public function getStreamTagId() : int
	{
		return $this->STREAMTAGID;
	}

	public function setStreamTagId(int $stream_tag_id) : void
	{
		$this->STREAMTAGID = $stream_tag_id;
	}

	public function getTagId() : int
	{
		return $this->TAGID;
	}

	public function setTagId(int $tag_id) : void
	{
		$this->TAGID = $tag_id;
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
		return $this->getStreamTagId();
	}

	public function setPrimaryKey(string|int $pk_value) : void
	{
		$this->setStreamTagId($pk_value);
	}

	public function toArray() : array
	{
		return [
			'STREAMTAGID' => $this->getStreamTagId(),
			'TAGID'       => $this->getTagId(),
			'STREAMID'    => $this->getStreamId()
		];
	}

	public function toPublicArray() : array
	{
		return $this->toArray();
	}

	public function getEntityPath() : string
	{
		return '\Porthorian\StreamStats\Modules\Streams\Tags\StreamTagEntity';
	}
}