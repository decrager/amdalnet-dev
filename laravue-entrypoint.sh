#!/bin/sh
set -eu

ENV_FILE=".env"
DB_HOST=$(cat $ENV_FILE | grep "DB_HOST" | cut -d"=" -f2)
DB_PORT=$(cat $ENV_FILE | grep "DB_PORT" | cut -d"=" -f2)

composer install

echo "Wait for Database to be ready"
while true;
do
  nc -z $DB_HOST $DB_PORT && break
  sleep 1
done

MIGRATE_NOT_MIGRATED_STATUS=$(php artisan migrate:status | grep "not found" | wc -l)
if [ $MIGRATE_NOT_MIGRATED_STATUS = "1" ];
then
  echo "Database seeding!"
  php artisan migrate --seed
fi

echo "NPM Install"
npm install -g cross-env && npm install && npm run production

echo "Serve"
php artisan serve --host 0.0.0.0

