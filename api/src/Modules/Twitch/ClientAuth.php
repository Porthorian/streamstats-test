<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Twitch;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use Porthorian\StreamStats\Cache\Cache;
use Porthorian\Utility\Json\JsonWrapper;
use Porthorian\Utility\Time\TimeCodes;

class ClientAuth
{
	private string $client_id;
	private string $client_secret;

	private string $bearer_token;
	private string $access_token;

	private string $code = '';
	private string $refresh_token = '';

	private bool $authenticated = false;

	private const BEARER_TOKEN_KEY = 'twitch:bearer_token';

	public function __construct(string $client_id, string $client_secret)
	{
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
	}

	public function cacheClientBearerToken() : void
	{
		Cache::set(self::BEARER_TOKEN_KEY, $this->getAccessToken(), TimeCodes::ONE_HOUR);
	}

	public function setCachedBearerToken() : void
	{
		$access_token = Cache::get(self::BEARER_TOKEN_KEY);
		if ($access_token === null)
		{
			return;
		}

		$this->setAccessToken($access_token);
	}

	public function authenticate() : void
	{
		try
		{
			$response = (new GuzzleClient())->post('https://id.twitch.tv/oauth2/token', ['form_params' => $this->generateAuthCodeGrantParams()]);
		}
		catch (Exception $e)
		{
			throw new TwitchException('Failed to authenticate to twitch.', $e);
		}

		$decoded = JsonWrapper::decode((string)$response->getBody(), JSON_THROW_ON_ERROR);
		if ($decoded['token_type'] != 'bearer')
		{
			throw new TwitchException('Unsupported token type. Expected bearer token.');
		}

		$this->setAccessToken($decoded['access_token']);
		if (isset($decoded['refresh_token']))
		{
			$this->setRefreshToken($decoded['refresh_token']);
			Cache::set('twitch:refresh_token:'.$this->getAccessToken(), $decoded['refresh_token'], (int)($decoded['expires_in'] * 2));
		}
	}

	// @TODO
	public function validate() : void
	{
		try
		{
			$response = (new GuzzleClient())->get('https://id.twitch.tv/oauth2/validate', [
				'headers' => [
					'Authorization' => $this->getBearerToken(),
				]
			]);
		}
		catch (Exception $e)
		{
			throw new TwitchException('Failed to validate twitch token.', $e);
		}
	}

	public function isAuthenticated() : bool
	{
		return $this->authenticated;
	}

	public function getClientId() : string
	{
		return $this->client_id;
	}

	public function getBearerToken() : string
	{
		return $this->bearer_token;
	}

	public function getAccessToken() : string
	{
		return $this->access_token;
	}

	public function setCode(string $code) : void
	{
		$this->code = $code;
	}

	public function setRefreshToken(string $token) : void
	{
		$this->refresh_token = $token;
	}

	public function setAccessToken(string $token)
	{
		$this->access_token = $token;
		$this->bearer_token = 'Bearer '.$token;
		$this->authenticated = true;
	}

	private function generateAuthCodeGrantParams() : array
	{
		/**
		 * For app specific actions that don't require specific user permission information.
		 */
		$params = [
			'client_id' => $this->client_id,
			'client_secret' => $this->client_secret,
			'grant_type' => 'client_credentials'
		];

		/**
		 * For user specific actions related to the oauth flow.
		 */
		if ($this->code != '')
		{
			$params['code'] = $this->code;
			$params['grant_type'] = 'authorization_code';
			$params['redirect_uri'] = Client::generateRedirectUri();
			return $params;
		}

		/**
		 * Our access token has expired.
		 * Lets refresh it.
		 */
		if ($this->refresh_token != '')
		{
			$params['grant_type'] = 'refresh_token';
			$params['refresh_token'] = $this->refresh_token;
		}

		return $params;
	}
}
