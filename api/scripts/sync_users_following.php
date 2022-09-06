<?php
declare(strict_types=1);

require __DIR__.'/../includes/init_script.php';

use Porthorian\StreamStats\Modules\Users\UserEntity;

echo "Syncing User Following\n";
echo "Checking for active streams that the users are following\n";
foreach ((new UserEntity())->getAllUsers() as $user)
{
	passthru('php '.__DIR__.'/sync_user_following.php '.$user->getUserId());
}
echo "User Following Sync Complete\n";