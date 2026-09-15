#!/bin/sh
set -e

# sqlite needs the file to exist before migrate; pgsql/mysql skip this.
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    DB_FILE="${DB_DATABASE:-/app/database/database.sqlite}"
    [ -f "$DB_FILE" ] || touch "$DB_FILE"
fi

php artisan migrate --force
php artisan config:cache

exec "$@"
