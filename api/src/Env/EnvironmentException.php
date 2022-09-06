<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Env;

use Exception;
use Throwable;

class EnvironmentException extends Exception
{
	public function __construct(string $message, ?Throwable $previous = null)
	{
		parent::__construct($message, 234, $previous);
	}
}
