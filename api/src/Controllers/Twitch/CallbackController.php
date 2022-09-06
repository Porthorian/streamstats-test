<?php

declare(strict_types=1);

namespace Porthorian\StreamStats\Controllers\Twitch;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Porthorian\StreamStats\Session;
use Porthorian\StreamStats\Env\Environment;
use Porthorian\StreamStats\Env\Config;
use Porthorian\StreamStats\Cache\Cache;

class CallbackController
{
	public function login(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		$params = $request->getQueryParams();
		if ((Session::get('csrf_token') ?? 'unknown') != ($params['state'] ?? ''))
		{
			return $response->withStatus(403);
		}

		echo "<pre>";
		var_dump($request->getQueryParams());
		echo "</pre>";
		return $response;
	}

	public function sendRedirect(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		$redirect_uri = 'https://api.streamstats.kriekon.com/twitch/callback';
		if (Environment::isDevEnv())
		{
			$redirect_uri = 'http://localhost/twitch/callback';
		}

		$url = 'https://id.twitch.tv/oauth2/authorize?response_type=code&client_id='.Config::getTwitchClientId();
		$url .= '&redirect_uri='.$redirect_uri;
		$url .= '&scope='.urlencode('user:read:follows user:read:email');
		$url .= '&state='.Session::get('csrf_token');
		return $response->withHeader('Location', $url)->withStatus(302);
	}
}
