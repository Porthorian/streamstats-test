<?php
declare(strict_types=1);

require __DIR__.'/../includes/init_script.php';

use Porthorian\StreamStats\Env\Config;
use Porthorian\StreamStats\Modules\Twitch\Client as TwitchClient;
use Porthorian\StreamStats\Modules\Twitch\ClientAuth;
use Porthorian\StreamStats\Modules\Users\UserEntity;
use Porthorian\StreamStats\Modules\Streams\StreamEntity;
use Porthorian\StreamStats\Modules\Streams\Following;
use Porthorian\PDOWrapper\DBWrapper;

echo "Syncing User Following\n";
echo "Checking for active streams that the users are following\n";
foreach ((new UserEntity())->getAllUsers() as $user)
{
	echo $user->getUserId().': Name: '.$user->getUsername().PHP_EOL;
	$auth = new ClientAuth(Config::getTwitchClientId(), Config::getTwitchClientSecret());
	$auth->setAccessToken($user->getAccessToken());

	$client = new TwitchClient($auth);
	$cursor = '';
	DBWrapper::startTransaction();
	DBWrapper::PExecute('DELETE FROM users_following_streams WHERE USERID = ?', [$user->getUserId()]);
	$ids = [];
	while (true)
	{
		$twitch_stream_ids = [];
		foreach ($client->getFollowedStreams($user->getTwitchUserId(), $cursor) as $data)
		{
			$twitch_stream_ids[] = $data['id'];
		}

		foreach ((new StreamEntity())->getStreamsByTwitchStreamId($twitch_stream_ids) as $stream)
		{
			echo $user->getUserId().': Stream: '.$stream->getStreamId().' - '.$stream->getStreamTitle().PHP_EOL;
			if (isset($ids[$stream->getStreamId()]))
			{
				continue;
			}

			$following = new Following();
			$following->setUserId($user->getUserId());
			$following->setStreamId($stream->getStreamId());

			$following->createEntity()->store();
			$ids[$stream->getStreamId()] = true;
		}

		if ($cursor == '')
		{
			break;
		}
	}
	DBWrapper::commitTransaction();
}
echo "User Following Sync Complete\n";
