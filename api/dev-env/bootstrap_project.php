<?php
declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use Porthorian\PDOWrapper\DBPool;
use Porthorian\PDOWrapper\Exception\DatabaseException;
use Porthorian\PDOWrapper\Models\DatabaseModel;
use Porthorian\PDOWrapper\DBWrapper;

$model = new DatabaseModel('streamstats', getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASS'));
$model->setPort((int)getenv('DB_PORT'));

DBPool::addPool($model);

echo "Authenticating to database server...\n";
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
	break;
}

$contents = file_get_contents(__DIR__.'/../db-files/streamstats_schema.sql');
$statements = explode(';', $contents);
foreach ($statements as $statement)
{
	if (empty($statement)) continue;
	echo "Executing Statement: ${statement}\n";
	DBWrapper::Execute($statement);
}

exec("php-fpm");
