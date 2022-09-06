<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Games;

use Porthorian\DBEntity\DBEntity;

class GameEntity extends DBEntity
{
	public function getModelPath() : string
	{
		return '\Porthorian\StreamStats\Modules\Games\Game';
	}

	public function getCollectionTable() : string
	{
		return 'games';
	}

	public function getCollectionPrimaryKey() : string
	{
		return 'GAMEID';
	}
}