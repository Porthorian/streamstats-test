<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Porthorian\StreamStats\Session;
use Porthorian\StreamStats\Util\ResponseHelper;
use Porthorian\StreamStats\Modules\Users\UserEntity;

class AuthController
{
	public function logout(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		$response = ResponseHelper::cors($response);

		Session::destroy();

		return $response->withStatus(204);
	}

	public function getLoggedInUser(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		return ResponseHelper::success($response, (new UserEntity())->find(Session::get('user_id_logged_in'))->toPublicArray());
	}
}
