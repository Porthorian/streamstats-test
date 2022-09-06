<?php
declare(strict_types=1);

require __DIR__.'/../includes/init_script.php';

use Porthorian\StreamStats\Env\Config;
use Porthorian\StreamStats\Modules\Twitch\Client as TwitchClient;
use Porthorian\StreamStats\Modules\Twitch\ClientAuth;
use Porthorian\StreamStats\Modules\Users\UserEntity;
use Porthorian\StreamStats\Modules\Streams\StreamEntity;
use Porthorian\StreamStats\Modules\Streams\Following;
use Porthorian\StreamStats\Controllers\StreamsController;
use Porthorian\PDOWrapper\DBWrapper;

$user = (new UserEntity())->find($argv[1]);

echo $user->getUserId().': Name: '.$user->getUsername().PHP_EOL;
$auth = new ClientAuth(Config::getTwitchClientId(), Config::getTwitchClientSecret());
$auth->setAccessToken($user->getAccessToken());

$client = new TwitchClient($auth);
$cursor = '';
DBWrapper::startTransaction();
DBWrapper::PExecute('DELETE FROM users_following_streams WHERE USERID = ?', [$user->getUserId()]);

StreamsController::populateInitialStreamsUserMap($client, $user);

DBWrapper::commitTransaction();
