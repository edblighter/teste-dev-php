#!/usr/bin/env bash

sudo PWD=${PWD} docker compose up -d --build
sudo docker exec -it app_php compose install --working-dir=/var/www/revendamais
sudo docker exec -it app_php cp revendamais/.env.example revendamais/.env
sudo docker exec -it app_php php revendamais/artisan key:generate
sudo docker exec -it app_php php revendamais/artisan migrate:fresh --seed
