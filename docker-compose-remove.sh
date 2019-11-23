#! /usr/bin/bash

docker stop vi1_mysql_1
docker stop vi1_nginx_1
docker stop vi1_pma_1
docker stop vi1_php_1
docker rm -v $(docker ps -aq -f status=exited)
