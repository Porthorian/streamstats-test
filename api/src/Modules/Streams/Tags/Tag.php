<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Streams\Tags;

use Porthorian\EntityOrm\Model\Model;
use Porthorian\Utility\Time\TimeCodes;

class Tag extends Model
{
	protected int $TAGID = 0;
	protected string $TWITCHTAGID = '';
	protected string $name = '';
	protected string $description = '';
	protected string $last_seen = TimeCodes::DATE_ZERO;

	public function getTagId() : int
	{
		return $this->TAGID;
	}

	public function setTagId(int $tag_id) : void
	{
		$this->TAGID = $tag_id;
	}

	public function getTwitchTagId() : string
	{
		return $this->TWITCHTAGID;
	}

	public function setTwitchTagId(string $twitch_tag_id) : void
	{
		$this->TWITCHTAGID = $twitch_tag_id;
	}

	public function getName() : string
	{
		return $this->name;
	}

	public function setName(string $name) : void
	{
		$this->name = $name;
	}

	public function getDescription() : string
	{
		return $this->description;
	}

	public function setDescription(string $description) : void
	{
		$this->description = $description;
	}

	public function getLastSeen() : string
	{
		return $this->last_seen;
	}

	public function setLastSeen(string $date_time) : void
	{
		$this->last_seen = $date_time;
	}

	////
	// Interface routines
	////

	public function getPrimaryKey() : string|int
	{
		return $this->getTagId();
	}

	public function setPrimaryKey(string|int $pk_value) : void
	{
		$this->setTagId($pk_value);
	}

	public function toArray() : array
	{
		return [
			'TAGID'       => $this->getTagId(),
			'TWITCHTAGID' => $this->getTwitchTagId(),
			'name'        => $this->getName(),
			'description' => $this->getDescription(),
			'last_seen'   => $this->getLastSeen()
		];
	}

	public function toPublicArray() : array
	{
		$array = $this->toArray();
		unset($array['last_seen']);
		return $array;
	}

	public function getEntityPath() : string
	{
		return '\Porthorian\StreamStats\Modules\Streams\Tags\TagEntity';
	}
}