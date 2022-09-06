<?php

declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Twitch;

use GuzzleHttp\Client as GuzzleClient;

class Client
{
	private GuzzleClient $guzzle;

	/**
	 * There is no point in allowing dependency injection for the HTTP Client as this isn't a package isn't going to be used by other developers.
	 * So its fine to couple this class to guzzle. Since its being installed anyways.
	 */
	public function __construct()
	{
		$this->guzzle = new GuzzleClient();
	}
}
