#! /bin/bash
cd ./docker/

docker compose up --build --remove-orphans -d
docker compose run workspace composer install
docker compose run workspace cp -n .env.example .env
docker compose run workspace php artisan migrate:fresh --seed
