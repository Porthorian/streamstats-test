<?php
declare(strict_types=1);

require __DIR__.'/../includes/init_script.php';

$location = __DIR__;
passthru("php ${location}/sync_games.php");
passthru("php ${location}/sync_streams.php");

