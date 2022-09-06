<?php
declare(strict_types=1);

require __DIR__.'/../includes/init_script.php';

$location = __DIR__;
$exit = passthru("php ${location}/sync_games.php");
if ($exit != 0)
{
	throw new Exception('Sync games failed.');
}
passthru("php ${location}/sync_streams.php");

