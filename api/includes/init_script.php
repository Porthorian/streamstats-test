<?php
declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use Porthorian\PDOWrapper\DBPool;
use Porthorian\PDOWrapper\DatabaseModel;
use Porthorian\PDOWrapper\DBWrapper;
use Porthorian\StreamStats\Env\Config;

Config::setConfig(__DIR__.'/../settings.json');

foreach (Config::getDatabases() as $model)
{
	DBPool::addPool($model);
}

DBWrapper::setDefaultDB('streamstats');

