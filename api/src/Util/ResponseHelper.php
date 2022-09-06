<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Util;

use Psr\Http\Message\ResponseInterface;
use Porthorian\Utility\Json\JsonWrapper;

class ResponseHelper
{
	public static function success(ResponseInterface $response, array $array, int $code = 200) : ResponseInterface
	{
		$response->getBody()->write(JsonWrapper::json(['data' => $array]));
		return $response->withHeader('Content-type', 'application/json')->withStatus($code);
	}

	public static function error(ResponseInterface $response, int $code = 400, array $array = []) : ResponseInterface
	{
		if (!empty($array))
		{
			$response->getBody()->write(JsonWrapper::json($array));
			$response = $response->withHeader('Content-type', 'application/json');
		}

		return $response->withStatus($code);
	}

	public static function errorMessage(ResponseInterface $response, string $message, int $code = 400) : ResponseInterface
	{
		return self::error($response, $code, ['message' => $message]);
	}
}