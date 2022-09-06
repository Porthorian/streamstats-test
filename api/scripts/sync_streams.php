<?php
declare(strict_types=1);

require __DIR__.'/../includes/init_script.php';

use Porthorian\StreamStats\Env\Config;
use Porthorian\StreamStats\Modules\Twitch\Client as TwitchClient;
use Porthorian\StreamStats\Modules\Twitch\ClientAuth;
use Porthorian\StreamStats\Modules\Streams\Stream;
use Porthorian\StreamStats\Modules\Streams\StreamEntity;
use Porthorian\StreamStats\Modules\Streams\Tags\StreamTag;
use Porthorian\StreamStats\Modules\Streams\Tags\Tag;
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

echo "Syncing Streams".PHP_EOL;
syncStreams($twitch_client);
echo "Done Syncing Streams".PHP_EOL;
exit(0);

function syncStreams(TwitchClient $twitch)
{
	$cursor = '';
	DBWrapper::startTransaction();
	$ids = [];
	$last_ran = DBWrapper::PSingle('SELECT MAX(last_seen) AS last_ran FROM streams')['last_ran'];
	while (true)
	{
		$streams = $twitch->getStreams(null, null, $cursor);

		$known_streams = (new StreamEntity())->getStreamsByTwitchStreamId(array_map(function ($value) {
			return $value['id'];
		}, $streams));

		$known_games = (new GameEntity())->getGamesByTwitchId(array_map(function ($value) {
			return $value['game_id'];
		}, $streams));

		shuffle($streams);
		foreach ($streams as $data)
		{
			echo "Stream ".$data['id'].'): Game: '.$data['game_id'].'): '.$data['title'].PHP_EOL;

			if (isset($known_streams[$data['id']]))
			{
				$update_stream = $known_streams[$data['id']];
				$update_stream->setStreamTitle($data['title']);
				$update_stream->setViewers($data['viewer_count']);
				$update_stream->setDateStarted($data['started_at']);
				$update_stream->setLastSeen(date(TimeCodes::DATEFORMAT_STANDARD));
				$params = ['stream_title', 'viewers', 'date_started', 'last_seen'];
				if (isset($known_games[$data['game_id']]))
				{
					$params[] = 'GAMEID';
					$update_stream->setGameId($known_games[$data['game_id']]->getGameId());
				}
				$update_stream->createEntity()->update($params);
				syncTagsToStream($update_stream, $data['tag_ids'] ?? []);
				continue;
			}

			/**
			 * there may be duplicate or missing streams, as viewers join and leave streams
			 */
			if (isset($ids[$data['id']]))
			{
				continue;
			}
			$ids[$data['id']] = true;

			$stream = new Stream();
			$stream->setTwitchStreamId($data['id']);
			$stream->setGameId(($known_games[$data['game_id']] ?? null)?->getGameId() ?? null);
			$stream->setStreamTitle($data['title']);
			$stream->setViewers($data['viewer_count']);
			$stream->setDateStarted($data['started_at']);
			$stream->setLastSeen(date(TimeCodes::DATEFORMAT_STANDARD));
			$stream->createEntity()->store();
			syncTagsToStream($stream, $data['tag_ids'] ?? []);
		}

		if ($cursor == '')
		{
			break;
		}
	}

	DBWrapper::factory('DELETE FROM streams WHERE last_seen < DATE_SUB(?, INTERVAL 20 MINUTE)', [$last_ran]);
	DBWrapper::commitTransaction();
}

function syncTagsToStream(Stream $stream, array $tags) : void
{
	$known_tags = (new Tag())->createEntity()->getTagsByTwitchId($tags);
	DBWrapper::PExecute('DELETE FROM stream_tags WHERE STREAMID = ?', [$stream->getStreamId()]);
	foreach ($tags as $tag_id)
	{
		if (!isset($known_tags[$tag_id]))
		{
			continue;
		}
		echo "Stream ".$stream->getTwitchStreamId()." Tag: ".$tag_id.PHP_EOL;

		$tag = $known_tags[$tag_id];
		$stream_tag =  new StreamTag();
		$stream_tag->setTagId($tag->getTagId());
		$stream_tag->setStreamId($stream->getStreamId());
		$stream_tag->createEntity()->store();
	}
}