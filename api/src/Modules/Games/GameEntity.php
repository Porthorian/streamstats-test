<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Games;

use Porthorian\DBEntity\DBEntity;
use Porthorian\PDOWrapper\DBWrapper;
use Porthorian\StreamStats\Util\DataFormat;

class GameEntity extends DBEntity
{
	public function getGamesByTwitchId(array $twitch_game_ids) : array
	{
		if (empty($twitch_game_ids))
		{
			return [];
		}

		$results = DBWrapper::PResult('
			SELECT * FROM games WHERE TWITCHGAMEID IN ('.DataFormat::getCommaListFromArray($twitch_game_ids).')
		');

		$output = [];
		foreach ($results as $result)
		{
			$game = new Game();
			$game->setModelProperties($result);
			$game->setInitializedFlag(true);
			$output[$result['TWITCHGAMEID']] = $game;
		}
		return $output;
	}

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
