<?php

declare(strict_types=1);

namespace Porthorian\StreamStats\Controllers\Twitch;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Porthorian\StreamStats\Session;
use Porthorian\StreamStats\Env\Config;
use Porthorian\StreamStats\Modules\Twitch\ClientAuth as TwitchAuth;
use Porthorian\StreamStats\Modules\Twitch\Client as TwitchClient;
use Porthorian\StreamStats\Modules\Twitch\TwitchException;
use Porthorian\StreamStats\Modules\Users\UserEntity;

class CallbackController
{
	public function login(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		$params = $request->getQueryParams();
		if ((Session::get('csrf_token') ?? 'unknown') != ($params['state'] ?? ''))
		{
			return $response->withStatus(403);
		}

		$auth = new TwitchAuth(Config::getTwitchClientId(), Config::getTwitchClientSecret());
		$auth->setCode($params['code'] ?? 'bad token');
		try
		{
			$auth->authenticate();
		}
		catch (TwitchException $e)
		{
			return $response->withStatus(403);
		}

		try
		{
			$auth->validate();
		}
		catch (TwitchException $e)
		{
			return $response->withStatus(403);
		}

		$client = new TwitchClient($auth);

		$twitch_user = $client->getUsers()[0];

		$entity = new UserEntity();
		$user = $entity->findByTwitchId($twitch_user['id']);
		if (!$user->isInitialized())
		{
			$user->reset();
			$user->setTwitchUserId($twitch_user['id']);
			$user->setEmail($twitch_user['email']);
			$user->setUsername($twitch_user['login']);

			$entity = $user->createEntity();
			$user = $entity->store();
		}

		$user->setAccessToken($auth->getAccessToken());
		$user->setRefreshToken($auth->getRefreshToken());
		$user->createEntity()->update(['access_token', 'refresh_token']);

		Session::start();
		Session::set('user_id_logged_in', $user->getUserId());

		$response->getBody()->write('logged in');
		return $response->withStatus(200);
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
