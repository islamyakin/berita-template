# syntax=docker/dockerfile:1

# --- build front-end assets ---
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources resources
COPY vite.config.js ./
RUN npm run build

# --- runtime: FrankenPHP worker mode ---
FROM dunglas/frankenphp:1-php8.4
WORKDIR /app

RUN install-php-extensions pdo_pgsql pcntl opcache zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist --optimize-autoloader

COPY . .
COPY --from=assets /app/public/build public/build
RUN composer run-script post-autoload-dump

COPY docker-entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

ENV OCTANE_SERVER=frankenphp APP_ENV=production
EXPOSE 8000
ENTRYPOINT ["entrypoint"]
# --workers/--max-requests tune per box; defaults are fine to start.
CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000"]
