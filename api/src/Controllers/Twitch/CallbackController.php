<?php

declare(strict_types=1);

namespace Porthorian\StreamStats\Controllers\Twitch;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Porthorian\StreamStats\Session;
use Porthorian\StreamStats\Env\Config;
use Porthorian\StreamStats\Modules\Twitch\ClientAuth as TwitchAuth;
use Porthorian\StreamStats\Modules\Twitch\Client as TwitchClient;

class CallbackController
{
	public function login(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		$params = $request->getQueryParams();
		if ((Session::get('csrf_token') ?? 'unknown') != ($params['state'] ?? ''))
		{
			return $response->withStatus(403);
		}

		echo "Loading...";
		$auth = new TwitchAuth(Config::getTwitchClientId(), Config::getTwitchClientSecret(), $params['code']);
		if (!$auth->isAuthenticated())
		{
			$auth->authenticate();
		}
		$client = new TwitchClient($auth);

		echo "<pre>";
		var_dump($client->getUsers()[0]);
		echo "</pre>";
		return $response;
	}

	public function sendRedirect(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		$redirect_uri = TwitchClient::generateRedirectUri();

		$url = 'https://id.twitch.tv/oauth2/authorize?response_type=code&client_id='.Config::getTwitchClientId();
		$url .= '&redirect_uri='.$redirect_uri;
		$url .= '&scope='.urlencode('user:read:follows user:read:email');
		$url .= '&state='.Session::get('csrf_token');
		return $response->withHeader('Location', $url)->withStatus(302);
	}
}
