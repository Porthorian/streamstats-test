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
		return self::cors($response)
			->withHeader('Content-type', 'application/json')
			->withHeader('Content-Length', $response->getBody()->getSize())
			->withStatus($code);
	}

	public static function error(ResponseInterface $response, int $code = 400, array $array = []) : ResponseInterface
	{
		if (!empty($array))
		{
			$response->getBody()->write(JsonWrapper::json($array));
			$response = $response
				->withHeader('Content-type', 'application/json')
				->withHeader('Content-Length', $response->getBody()->getSize());
		}

		return self::cors($response)->withStatus($code);
	}

	public static function errorMessage(ResponseInterface $response, string $message, int $code = 400) : ResponseInterface
	{
		return self::error($response, $code, ['error' => $message]);
	}

	public static function cors(ResponseInterface $response) : ResponseInterface
	{
		return $response
			->withHeader('Access-Control-Allow-Origin', 'http://localhost:8080')
			->withHeader('Access-Control-Allow-Credentials', 'true')
			->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
			->withHeader('Access-Control-Allow-Methods', 'GET');
	}
}