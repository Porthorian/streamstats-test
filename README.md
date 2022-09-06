# streamstats-test

https://streamstats.kriekon.com

There is a cron running on the server with the following parameters.

```
*/15 * * * *      root     /usr/bin/php /var/www/streamstats.kriekon.com/api/scripts/seed_database.php 2>&1 >> /tmp/streamstats_test.log
```

Run development server

Will launch, mysql, redis, php, nginx containers.
```
cd ./api && sudo docker-compose up
```

To run the frontend application
Than navigate to localhost:8080
```
cd ./client && npm run serve
```

To seed the database with data initially
```
sudo docker ps 

# get the docker id for the php container


sudo docker exec -it {Your-docker-container-id} php /home/app/scripts/seed_database.php
```