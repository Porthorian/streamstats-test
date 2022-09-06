<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Games;

use Porthorian\EntityOrm\Model\Model;
use Porthorian\Utility\Time\TimeCodes;

class Game extends Model
{
	protected int $GAMEID = 0;
	protected string $TWITCHGAMEID = '';
	protected string $game_name = '';
	protected string $last_seen = TimeCodes::DATE_ZERO;

	public function getGameId() : int
	{
		return $this->GAMEID;
	}

	public function setGameId(int $GAMEID) : void
	{
		$this->GAMEID = $GAMEID;
	}

	public function getTwitchGameId() : string
	{
		return $this->TWITCHGAMEID;
	}

	public function setTwitchGameId(string $TWITCHGAMEID) : void
	{
		$this->TWITCHGAMEID = $TWITCHGAMEID;
	}

	public function getGameName() : string
	{
		return $this->game_name;
	}

	public function setGameName(string $game_name) : void
	{
		$this->game_name = $game_name;
	}

	public function getLastSeen() : string
	{
		return $this->last_seen;
	}

	public function setLastSeen(string $timestamp) : void
	{
		$this->last_seen = $timestamp;
	}

	////
	// Interface routines
	////

	public function getPrimaryKey() : string|int
	{
		return $this->getGameId();
	}

	public function setPrimaryKey(string|int $pk_value) : void
	{
		$this->setGameId($pk_value);
	}

	public function toArray() : array
	{
		return [
			'GAMEID' => $this->getGameId(),
			'TWITCHGAMEID' => $this->getTwitchGameId(),
			'game_name' => $this->getGameName(),
			'last_seen' => $this->getLastSeen()
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
		return '\Porthorian\StreamStats\Modules\Games\GameEntity';
	}
}
