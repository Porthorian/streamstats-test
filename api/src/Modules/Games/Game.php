<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Games;

use Porthorian\EntityOrm\Model\Model;

class Game extends Model
{
	protected int $GAMEID = 0;
	protected string $TWITCHGAMEID = '';
	protected string $game_name = '';

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
			'game_name' => $this->getGameName()
		];
	}

	public function toPublicArray() : array
	{
		return $this->toArray();
	}

	public function getEntityPath() : string
	{
		return '\Porthorian\StreamStats\Modules\Games\GameEntity';
	}
}
