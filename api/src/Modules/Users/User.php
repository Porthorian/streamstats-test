<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Users;

use Porthorian\EntityOrm\Model\Model;

class User extends Model
{
	protected int $USERID = 0;
	protected string $TWITCHUSERID = '';
	protected string $email = '';
	protected string $username = '';
	protected string $access_token = '';
	protected string $refresh_token = '';

	public function getUserId() : int
	{
		return $this->USERID;
	}

	public function setUserId(int $USERID) : void
	{
		$this->USERID = $USERID;
	}

	public function getTwitchUserId() : string
	{
		return $this->TWITCHUSERID;
	}

	public function setTwitchUserId(string $TWITCHUSERID) : void
	{
		$this->TWITCHUSERID = $TWITCHUSERID;
	}

	public function getEmail() : string
	{
		return $this->email;
	}

	public function setEmail(string $email) : void
	{
		$this->email = $email;
	}

	public function getUsername() : string
	{
		return $this->username;
	}

	public function setUsername(string $username) : void
	{
		$this->username = $username;
	}

	public function getAccessToken() : string
	{
		return $this->access_token;
	}

	public function setAccessToken(string $access_token) : void
	{
		$this->access_token = $access_token;
	}

	public function getRefreshToken() : string
	{
		return $this->refresh_token;
	}

	public function setRefreshToken(string $refresh_token) : void
	{
		$this->refresh_token = $refresh_token;
	}

	////
	// Interface specific
	////

	public function toArray() : array
	{
		return [
			'USERID' => $this->getUserId(),
			'TWITCHUSERID' => $this->getTwitchUserId(),
			'email' => $this->getEmail(),
			'username' => $this->getUsername(),
			'access_token' => $this->getAccessToken(),
			'refresh_token' => $this->getRefreshToken()
		];
	}

	public function toPublicArray() : array
	{
		$array = $this->toArray();
		unset($array['access_token']);
		unset($array['refresh_token']);
		return $array;
	}

	public function setPrimaryKey(string|int $value) : void
	{
		$this->setUserId($value);
	}

	public function getPrimaryKey() : string|int
	{
		return $this->getUserId();
	}

	public function getEntityPath() : string
	{
		return '\Porthorian\StreamStats\Modules\Users\UserEntity';
	}
}
