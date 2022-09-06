<?php
declare(strict_types=1);

require __DIR__.'/../includes/init_script.php';

use Porthorian\StreamStats\Env\Config;
use Porthorian\StreamStats\Modules\Twitch\Client as TwitchClient;
use Porthorian\StreamStats\Modules\Twitch\ClientAuth;

$auth = new ClientAuth(Config::getTwitchClientId(), Config::getTwitchClientSecret());
if (!$auth->isAuthenticated())
{
	$auth->authenticate();
}

$twitch = new TwitchClient($auth);
var_dump($twitch->getUsers('24061575'));

