<?php
declare(strict_types=1);

require __DIR__.'/../includes/init_script.php';

use Porthorian\StreamStats\Env\Config;
use Porthorian\StreamStats\Modules\Twitch\Client as TwitchClient;
use Porthorian\StreamStats\Modules\Twitch\ClientAuth;
use Porthorian\StreamStats\Modules\Games\Game;
use Porthorian\StreamStats\Modules\Games\GameEntity;
use Porthorian\PDOWrapper\DBWrapper;
use Porthorian\Utility\Time\TimeCodes;

$auth = new ClientAuth(Config::getTwitchClientId(), Config::getTwitchClientSecret());
$auth->setCachedBearerToken();
if (!$auth->isAuthenticated())
{
	$auth->authenticate();
	$auth->cacheClientBearerToken();
}

$twitch_client = new TwitchClient($auth);

echo "Syncing Games".PHP_EOL;
syncGames($twitch_client);
echo "Done Syncing Games".PHP_EOL;
exit(0);

function syncGames(TwitchClient $twitch) : void
{
	$cursor = '';
	DBWrapper::startTransaction();
	$ids = [];
	$last_ran = DBWrapper::PSingle('SELECT MAX(last_seen) AS last_ran FROM games')['last_ran'];
	while (true)
	{
		$games = $twitch->getTopGames($cursor);

		$known_games = (new GameEntity())->getGamesByTwitchId(array_map(function ($value) {
			return $value['id'];
		}, $games));

		shuffle($games);
		foreach ($games as $data)
		{
			echo "Checking game ".$data['id'].': '.$data['name'].PHP_EOL;
			if (isset($known_games[$data['id']]))
			{
				$update_game = $known_games[$data['id']];
				$update_game->setGameName($data['name']);
				$update_game->setLastSeen(date(TimeCodes::DATEFORMAT_STANDARD));
				$update_entity = $update_game->createEntity();
				$update_entity->update(['game_name', 'last_seen']);
				continue;
			}

			/**
			 * Sometimes there are the same games in the paginated responses?
			 * Avoid duplicates.
			 * Why that is I have no clue.
			 */
			if (isset($ids[$data['id']]))
			{
				continue;
			}
			$ids[$data['id']] = true;

			$game = new Game();
			$game->setTwitchGameId($data['id']);
			$game->setGameName($data['name']);
			$game->setLastSeen(date(TimeCodes::DATEFORMAT_STANDARD));
			$entity = $game->createEntity();
			$entity->store();
		}

		if ($cursor == '')
		{
			break;
		}
	}
	DBWrapper::factory('DELETE FROM games WHERE last_seen < DATE_SUB(?, INTERVAL 20 MINUTE)', [$last_ran]);
	DBWrapper::commitTransaction();
}

