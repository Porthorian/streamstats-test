<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Games;

use Porthorian\DBEntity\DBEntity;
use Porthorian\PDOWrapper\DBWrapper;
use Porthorian\PDOWrapper\Models\DBResult;
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

	public function getTotalStreamsForEachGame() : DBResult
	{
		return DBWrapper::PResult('
			SELECT streams.GAMEID, games.game_name, COUNT(*) AS total_streams
			FROM streams
			LEFT JOIN games
				ON games.GAMEID = streams.GAMEID
			WHERE streams.GAMEID IS NOT NULL
			GROUP BY streams.GAMEID, games.game_name ORDER BY total_streams DESC
		');
	}

	public function getTopGamesByViewerCount() : DBResult
	{
		return DBWrapper::PResult('
			SELECT streams.GAMEID, games.game_name, SUM(streams.viewers) AS total_viewers
			FROM streams
			LEFT JOIN games
				ON games.GAMEID = streams.GAMEID
			WHERE streams.GAMEID IS NOT NULL
			GROUP BY streams.GAMEID, games.game_name
			ORDER BY total_viewers DESC;
		');
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
