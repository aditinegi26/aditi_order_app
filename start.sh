#!/usr/bin/env bash

red=$'\e[1;31m'
grn=$'\e[1;32m'
blu=$'\e[1;34m'
mag=$'\e[1;35m'
cyn=$'\e[1;36m'
white=$'\e[0m'

#sudo apt update
#sudo apt install -y curl

echo " $red ----- Initializing Docker Files ------- $white "
docker-compose down && docker-compose up --build -d

echo " $grn -------Installing Project Dependencies -----------$blu "
sleep 20s #this line is included for composer to finish the dependency installation so that test case can execute without error.

echo " $blu ----- Running Migrations & Data Seeding ------- $white "
#sudo chmod 777 -R ./code/*

docker exec manage_order_php php artisan migrate
docker exec manage_order_php php artisan db:seed

echo " $blu ----- Running Feature test cases ------- $white "
docker exec manage_order_php php ./vendor/phpunit/phpunit/phpunit tests/Feature/OrderTest.php

echo " $blu ----- Running Unit test cases ------- $white "
docker exec manage_order_php php ./vendor/phpunit/phpunit/phpunit tests/Unit

exit 0
