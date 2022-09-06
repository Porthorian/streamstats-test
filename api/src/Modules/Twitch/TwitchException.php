<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Twitch;

use Exception;
use Throwable;

class TwitchException extends Exception
{
	public function __construct(string $message, ?Throwable $previous = null)
	{
		parent::__construct($message, 603, $previous);
	}
}
