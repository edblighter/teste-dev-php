#!/usr/bin/env bash

PWD=${PWD} docker compose up -d --build
docker exec -it app_php sh -c "composer install --working-dir=/var/www/revendamais"
docker exec -it app_php sh -c "cp revendamais/.env.example revendamais/.env"
docker exec -it app_php sh -c "php revendamais/artisan key:generate"
docker exec -it app_php sh -c "php revendamais/artisan migrate:fresh --seed"
