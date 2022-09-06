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

	////
	// Interface specific
	////

	public function toArray() : array
	{
		return [
			'USERID' => $this->getUserId(),
			'TWITCHUSERID' => $this->getTwitchUserId(),
			'email' => $this->getEmail(),
			'username' => $this->getUsername()
		];
	}

	public function toPublicArray() : array
	{
		return $this->toArray();
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
