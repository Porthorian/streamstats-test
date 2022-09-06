# streamstats-test

https://streamstats.kriekon.com

There is a cron running on the server with the following parameters.

```
*/15 * * * *      root     /usr/bin/php /var/www/streamstats.kriekon.com/api/scripts/seed_database.php 2>&1 >> /tmp/streamstats_test.log
```
