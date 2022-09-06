<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Users;

use Porthorian\PDOWrapper\DBWrapper;
use Porthorian\PDOWrapper\Models\DBResult;

class UserList extends DBResult
{
	public function __construct()
	{
		parent::__construct(DBWrapper::factory('SELECT * FROM users'));
	}

	public function current() : User
	{
		$user = new User();
		$user->setModelProperties($this->getRecord());
		$user->setInitializedFlag(true);
		return $user;
	}
}