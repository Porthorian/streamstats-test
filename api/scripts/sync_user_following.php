<?php
declare(strict_types=1);

require __DIR__.'/../includes/init_script.php';

use Porthorian\StreamStats\Env\Config;
use Porthorian\StreamStats\Modules\Twitch\Client as TwitchClient;
use Porthorian\StreamStats\Modules\Twitch\ClientAuth;
use Porthorian\StreamStats\Modules\Users\UserEntity;

foreach ((new UserEntity())->getAllUsers() as $user)
{
	$auth = new ClientAuth(Config::getTwitchClientId(), Config::getTwitchClientSecret());
}

