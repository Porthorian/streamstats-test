<?php
declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use Porthorian\PDOWrapper\DBPool;
use Porthorian\PDOWrapper\Exception\DatabaseException;
use Porthorian\PDOWrapper\Models\DatabaseModel;
use Porthorian\PDOWrapper\DBWrapper;
use Porthorian\StreamStats\Env\Environment;

$model = waitAndConnectToDB();
generateSettings($model);
importSchema();

exec("php-fpm");
exit(0);

function generateSettings(DatabaseModel $model) : void
{
	echo "Generating Settings\n";
	$settings = [];
	$settings['environment'] = Environment::DEV;
	$settings['databases'] = [
		$model->getDBName() => [
			'host' => $model->getHost(),
			'port' => $model->getPort(),
			'user' => $model->getUser(),
			'password' => $model->getPassword(),
			'charset' => $model->getCharset()
		]
	];
	$settings['twitch'] = [
		'client_id' => 'wok76egoc5im83o5zmhi2eqnpx9rpf',
		'client_secret' => 'k762s72345k0xzi4k3emolb5e9p2rd'
	];
	$settings['redis'] = [
		'host' => getenv('REDIS_HOST'),
		'port' => (int)getenv('REDIS_PORT')
	];
	$settings['cookie_domain'] = 'localhost';
	$settings['home_url'] = 'http://localhost:8080';
	$settings['redirect_url'] = 'http://localhost/twitch/callback';
	var_dump($settings);
	file_put_contents(__DIR__.'/../settings.json', json_encode($settings, JSON_PRETTY_PRINT));
}

function importSchema() : void
{
	$contents = file_get_contents(__DIR__.'/../db-files/streamstats_schema.sql');
	$statements = explode(';', $contents);
	foreach ($statements as $statement)
	{
		if (empty($statement)) continue;
		echo "Executing Statement: ${statement}\n";
		DBWrapper::Execute($statement);
	}
}

function waitAndConnectToDB() : DatabaseModel
{
	$model = new DatabaseModel('streamstats', getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASS'), 'utf8mb4');
	$model->setPort((int)getenv('DB_PORT'));

	DBPool::addPool($model);

	echo "Authenticating to database server...\n";
	$failed = true;
	for ($i = 0; $i < 10; $i++)
	{
		try
		{
			DBPool::connectDatabase('streamstats');
		}
		catch (DatabaseException)
		{
			echo "Failed to connect retrying....\n";
			sleep(1);
			continue;
		}
		$failed = false;
		break;
	}

	if ($failed)
	{
		echo "Failed to connect to database.\n";
		exit(255);
	}

	return $model;
}
