<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Auth
{
	public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
	{
		return $handler->handle($request);
	}
}