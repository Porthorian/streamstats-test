<?php

declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Twitch;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use Porthorian\StreamStats\Env\Config;
use Porthorian\StreamStats\Env\Environment;
use Porthorian\Utility\Json\JsonWrapper;
use Porthorian\StreamStats\Cache\Cache;

class Client
{
	private GuzzleClient $guzzle;
	private ClientAuth $auth;

	/**
	 * There is no point in allowing dependency injection for the HTTP Client as this isn't a package isn't going to be used by other developers.
	 * So its fine to couple this class to guzzle. Since its being installed anyways.
	 */
	public function __construct(ClientAuth $auth)
	{
		$this->auth = $auth;
		$this->guzzle = new GuzzleClient([
			'base_uri' => 'https://api.twitch.tv',
			'headers' => [
				'Authorization' => $auth->getBearerToken(),
				'Client-Id'     => $auth->getClientId()
			]
		]);
	}

	////
	// Public routines
	////

	/**
	 * Get the user information for a single account.
	 * If no id or ids given.
	 * Must have been given a authorization token with the authorization code grant flow before using this function for a particular user.
	 * @return array
	 * Example response of objects
	 * array(1) {
			array(11) {
			  ["id"]=>
			  string(8) "24061575"
			  ["login"]=>
			  string(9) "mrviinnie"
			  ["display_name"]=>
			  string(9) "MrViinnie"
			  ["type"]=>
			  string(0) ""
			  ["broadcaster_type"]=>
			  string(0) ""
			  ["description"]=>
			  string(61) "Hey guys I broadcast to have fun and film the games I Love.  "
			  ["profile_image_url"]=>
			  string(109) "https://static-cdn.jtvnw.net/jtv_user_pictures/74f2947e-e804-4fb6-9327-3906026da5fa-profile_image-300x300.png"
			  ["offline_image_url"]=>
			  string(0) ""
			  ["view_count"]=>
			  int(1317)
			  ["email"]=> // If user:read:email is scoped
			  string(23) "xnjxdanger0us@gmail.com"
			  ["created_at"]=>
			  string(20) "2011-08-13T22:27:16Z"
			}
		}
	 */
	public function getUsers(string|array $id = '') : array
	{
		$this->checkAuthentication();

		$params = [];
		if (!empty($id) && is_string($id))
		{
			$params['query'] = ['id' => $id];
		}
		else if (!empty($id) && is_array($id))
		{
			$params['query'] = ['id' => $id];
		}

		try
		{
			$response = $this->guzzle->get('helix/users', $params);
		}
		catch (Exception $e)
		{
			throw new TwitchException('Failed to get users from twitch.', $e);
		}

		$decode = JsonWrapper::decode((string)$response->getBody());
		if ($decode === null)
		{
			throw new TwitchException('Failed to decode user response. Error: '.JsonWrapper::getLastError());
		}

		return $decode['data'];
	}

	public function getTopGames(string &$cursor = '') : array
	{
		$this->checkAuthentication();

		try
		{
			$params = [
				'query' => [
					'first' => 100
				]
			];
			if ($cursor != '')
			{
				$params['query']['after'] = $cursor;
			}
			$response = $this->guzzle->get('helix/games/top', $params);
		}
		catch (Exception $e)
		{
			throw new TwitchException('Failed to get top games from twitch.', $e);
		}

		$decode = JsonWrapper::decode((string)$response->getBody());
		if ($decode === null)
		{
			throw new TwitchException('Failed to decode games response. Error: '.JsonWrapper::getLastError());
		}

		$cursor = $decode['pagination']['cursor'] ?? '';
		return $decode['data'];
	}

	public function getStreams(?string $game_id = null, ?string $user_id = null, string &$cursor = '') : array
	{
		$this->checkAuthentication();

		try
		{
			$params = [
				'query' => [
					'first' => 100
				]
			];

			if ($game_id !== null)
			{
				$params['query']['game_id'] = $game_id;
			}

			if ($user_id !== null)
			{
				$params['query']['user_id'] = $user_id;
			}

			if ($cursor != '')
			{
				$params['query']['after'] = $cursor;
			}

			$response = $this->guzzle->get('helix/streams', $params);
		}
		catch (Exception $e)
		{
			throw new TwitchException('Failed to get twitch streams.', $e);
		}

		$decode = JsonWrapper::decode((string)$response->getBody());
		if ($decode === null)
		{
			throw new TwitchException('Failed to decode streams response. Error: '.JsonWrapper::getLastError());
		}

		$cursor = $decode['pagination']['cursor'] ?? '';
		return $decode['data'];
	}

	/**
	 * user_id must match the user_id in the bearer_token.
	 * @return array
	 */
	public function getFollowedStreams(string $user_id, string &$cursor = '') : array
	{
		$this->checkAuthentication();

		try
		{
			$params = [
				'query' => [
					'first' => 100,
					'user_id' => $user_id
				]
			];

			if ($cursor != '')
			{
				$params['query']['after'] = $cursor;
			}

			$response = $this->guzzle->get('helix/streams/followed', $params);
		}
		catch (Exception $e)
		{
			throw new TwitchException('Failed to get user followed streams', $e);
		}

		$decode = JsonWrapper::decode((string)$response->getBody());
		if ($decode === null)
		{
			throw new TwitchException('Failed to decode followed streams response. Error: '.JsonWrapper::getLastError());
		}

		$cursor = $decode['pagination']['cursor'] ?? '';
		return $decode['data'];
	}

	////
	// Public static routines
	////

	public static function generateRedirectUri() : string
	{
		$redirect_uri = 'https://api.streamstats.kriekon.com/twitch/callback';
		if (Environment::isDevEnv())
		{
			$redirect_uri = 'http://localhost/twitch/callback';
		}

		return $redirect_uri;
	}

	////
	// Private routines
	////

	private function checkAuthentication() : void
	{
		if ($this->auth->isAuthenticated())
		{
			return;
		}

		throw new TwitchException('Client is not authorized to make a request.');
	}
}
