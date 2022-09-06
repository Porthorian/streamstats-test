<?php
declare(strict_types=1);

require __DIR__.'/../includes/init_script.php';

$location = __DIR__;
$exit = passthru("php ${location}/sync_games.php");
if ($exit != 0)
{
	throw new Exception('Sync games failed.');
}

$exit = passthru("php ${location}/sync_tags.php");
if ($exit != 0)
{
	throw new Exception('Failed to sync tags.');
}

$exit = passthru("php ${location}/sync_streams.php");
if ($exit != 0)
{
	throw new Exception('Sync streams failed.');
}

passthru("php ${location}/sync_user_following.php");

