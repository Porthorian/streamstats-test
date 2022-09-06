<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpUnauthorizedException;
use Porthorian\StreamStats\Session;
use Porthorian\StreamStats\Modules\Twitch\ClientAuth;
use Porthorian\StreamStats\Modules\Twitch\TwitchException;
use Porthorian\StreamStats\Env\Config;
use Porthorian\StreamStats\Modules\Users\UserEntity;

class Auth
{
	public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
	{
		if (!(Session::has('user_id_logged_in')))
		{
			throw new HttpForbiddenException($request, 'Not logged in.');
		}

		$user = (new UserEntity())->find(Session::get('user_id_logged_in'));
		if (!$user->isInitialized())
		{
			Session::destroy();
			throw new HttpForbiddenException($request, 'User does not exist anymore.');
		}

		$auth = new ClientAuth(Config::getTwitchClientId(), Config::getTwitchClientSecret());
		$auth->setAccessToken($user->getAccessToken());

		try
		{
			$auth->validate();
		}
		catch (TwitchException $e)
		{
			$auth->setRefreshToken($user->getRefreshToken());
			try
			{
				$auth->authenticate();
			}
			catch (TwitchException)
			{
				Session::destroy();
				$user->createEntity()->delete();
				throw new HttpUnauthorizedException($request, 'Token or authorization to act as the user has been removed.', $e);
			}

			$user->setAccessToken($auth->getAccessToken());
			$user->setRefreshToken($auth->getRefreshToken());
			$user->createEntity()->update(['access_token', 'refresh_token']);
		}

		return $handler->handle($request);
	}
}
