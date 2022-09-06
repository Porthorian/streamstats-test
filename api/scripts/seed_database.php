<?php
declare(strict_types=1);

require __DIR__.'/../includes/init_script.php';

use Porthorian\StreamStats\Env\Config;
use Porthorian\StreamStats\Modules\Twitch\Client as TwitchClient;
use Porthorian\StreamStats\Modules\Twitch\ClientAuth;
use Porthorian\StreamStats\Modules\Games\Game;
use Porthorian\PDOWrapper\DBWrapper;

$auth = new ClientAuth(Config::getTwitchClientId(), Config::getTwitchClientSecret());
$auth->setCachedBearerToken();
if (!$auth->isAuthenticated())
{
	$auth->authenticate();
	$auth->cacheClientBearerToken();
}

$twitch = new TwitchClient($auth);

$cursor = '';
DBWrapper::startTransaction();
DBWrapper::factory('DELETE FROM games');
$ids = [];
while (true)
{
	$games = $twitch->getTopGames($cursor);
	shuffle($games);
	foreach ($games as $data)
	{
		if (isset($ids[$data['id']]))
		{
			continue;
		}
		$game = new Game();
		$game->setTwitchGameId($data['id']);
		$game->setGameName($data['name']);
		$entity = $game->createEntity();
		$entity->store();
		$ids[$data['id']] = true;
	}

	if ($cursor == '')
	{
		break;
	}
}
DBWrapper::commitTransaction();


