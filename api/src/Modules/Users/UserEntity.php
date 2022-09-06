<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Users;

use Porthorian\DBEntity\DBEntity;
use Porthorian\PDOWrapper\DBWrapper;

class UserEntity extends DBEntity
{
	public function findByTwitchId(string $twitch_id) : User
	{
		$this->setModel(new User());
		$this->setModelProperties(DBWrapper::PResult('SELECT * FROM users WHERE TWITCHUSERID = ?', [$twitch_id]));
		return $this->getModel();
	}

	public function getAllUsers() : UserList
	{
		return new UserList();
	}

	////
	// Interface routines
	////

	public function getCollectionTable() : string
	{
		return 'users';
	}

	public function getCollectionPrimaryKey() : string
	{
		return 'USERID';
	}

	public function getModelPath() : string
	{
		return '\Porthorian\StreamStats\Modules\Users\User';
	}
}
