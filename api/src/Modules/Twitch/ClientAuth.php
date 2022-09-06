<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Twitch;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use Porthorian\StreamStats\Cache\Cache;
use Porthorian\Utility\Json\JsonWrapper;

class ClientAuth
{
	private string $client_id;
	private string $client_secret;

	private string $bearer_token;
	private string $code = '';

	private bool $authenticated = false;

	private const BEARER_TOKEN_KEY = 'twitch:bearer_token';

	public function __construct(string $client_id, string $client_secret, string $code = '')
	{
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
		$this->code = $code;

		$bearer_token = Cache::get($this->getCacheKey());
		if ($bearer_token !== null)
		{
			$this->setBearerToken($bearer_token);
			return;
		}
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

		Cache::set($this->getCacheKey(), $decoded['access_token'], (int)($decoded['expires_in'] - ($decoded['expires_in'] / 10)));
		$this->setBearerToken($decoded['access_token']);
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

	private function setBearerToken(string $token)
	{
		$this->bearer_token = 'Bearer '.$token;
		$this->authenticated = true;
	}

	private function getCacheKey() : string
	{
		if ($this->code != '')
		{
			return self::BEARER_TOKEN_KEY.':'.$this->code;
		}

		return self::BEARER_TOKEN_KEY;
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
		}

		return $params;
	}
}
