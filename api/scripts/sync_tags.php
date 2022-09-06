<?php
declare(strict_types=1);

require __DIR__.'/../includes/init_script.php';

use Porthorian\StreamStats\Env\Config;
use Porthorian\StreamStats\Modules\Twitch\Client as TwitchClient;
use Porthorian\StreamStats\Modules\Twitch\ClientAuth;
use Porthorian\StreamStats\Modules\Streams\Tags\Tag;
use Porthorian\StreamStats\Modules\Streams\Tags\TagEntity;
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

$cursor = '';
$ids = [];
echo "Syncing Tags\n";
DBWrapper::startTransaction();
$last_ran = DBWrapper::PSingle('SELECT MAX(last_seen) AS last_ran FROM tags')['last_ran'];
while (true)
{
	$tags = $twitch_client->getAllStreamTags($cursor);

	$found_tags = (new TagEntity())->getTagsByTwitchId(array_map(function ($value) {
		return $value['tag_id'];
	}, $tags));

	shuffle($tags);
	foreach ($tags as $data)
	{
		echo 'Tag '.$data['tag_id'].'): '.$data['localization_names']['en-us'].PHP_EOL;
		if (isset($found_tags[$data['tag_id']]))
		{
			$update_tag = $found_tags[$data['tag_id']];
			$update_tag->setName($data['localization_names']['en-us']);
			$update_tag->setDescription($data['localization_descriptions']['en-us']);
			$update_tag->setLastSeen(date(TimeCodes::DATEFORMAT_STANDARD));
			$update_tag->createEntity()->update(['name', 'description', 'last_seen']);
			continue;
		}

		if (isset($ids[$data['tag_id']]))
		{
			continue;
		}

		$ids[$data['tag_id']] = true;

		$tag = new Tag();
		$tag->setTwitchTagId($data['tag_id']);
		$tag->setName($data['localization_names']['en-us']);
		$tag->setDescription($data['localization_descriptions']['en-us']);
		$tag->setLastSeen(date(TimeCodes::DATEFORMAT_STANDARD));
		$tag->createEntity()->store();
	}

	if ($cursor == '')
	{
		break;
	}
}
DBWrapper::factory('DELETE FROM tags WHERE last_seen < DATE_SUB(?, INTERVAL 20 MINUTE)', [$last_ran]);
DBWrapper::commitTransaction();
echo "Done syncing tags\n";