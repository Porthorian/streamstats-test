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

class Auth
{
	public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
	{
		if (!(Session::has('user_logged_in') && Session::has('twitch_bearer_token')))
		{
			throw new HttpForbiddenException($request, 'Not logged in.');
		}

		$auth = new ClientAuth(Config::getTwitchClientId(), Config::getTwitchClientSecret());
		$auth->setAccessToken(Session::get('twitch_bearer_token'));

		try
		{
			$auth->validate();
		}
		catch (TwitchException $e)
		{
			throw new HttpUnauthorizedException($request, 'Token or authorization to act as the user has been removed.', $e);
		}

		return $handler->handle($request);
	}
}
