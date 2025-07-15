FROM serversideup/php:8.3-fpm-nginx AS base

USER root


RUN install-php-extensions fileinfo gd pgsql mysqli intl

# Install additional system packages not found in base image
RUN apt-get update && apt-get install -y --no-install-recommends --fix-missing \
     apt-utils \
     ffmpeg \
     supervisor \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*


COPY ./docker/php.ini /usr/local/etc/php/conf.d/


WORKDIR /var/www/html

########################################################
# Stage 1: Builder
########################################################
FROM base AS builder

# Install Node.js and npm
USER root


RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get update \
    && apt-get install -y --no-install-recommends  nodejs \
    && npm install -g npm@latest \
    && rm -rf /var/lib/apt/lists/*

# Copy in PHP dependency manifests, install vendor packages
# This layer only changes when composer.json or composer.lock changes
COPY --chown=www-data:www-data composer.json composer.lock ./
USER www-data
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist


# Copy in JS dependency manifests, install front-end deps
# This layer only changes when package.json/package-lock.json changes
COPY --chown=www-data:www-data package.json package-lock.json ./
USER root
RUN npm ci

# Copy the rest of the application source (PHP, Vue components, assets, etc.)
COPY --chown=www-data:www-data . .

RUN chmod +x ./node_modules/.bin/vite

# Build your Vite/Inertia assets
USER www-data

RUN npm run build



########################################################
# Stage 2: Production
########################################################
FROM base AS production

# Enable environment variables that are recommends by Documentation

ENV AUTORUN_ENABLE=1
ENV AUTORUN_LARAVEL_MIGRATION_ISOLATION=1

# Switch to root user, set all public and storage directories
USER root

COPY --chmod=755 ./docker/entrypoint.d/ /etc/entrypoint.d/

RUN mkdir -p /var/www/html/storage/app/public \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/logs \
    && chown -R www-data:www-data /var/www/html/storage \
    && chmod -R 775 /var/www/html/storage

# Copy all of the items from builder to production and switch to user
COPY --chown=www-data:www-data --from=builder /var/www/html /var/www/html
USER www-data

# Ensure the public/storage symlink exists
RUN if [ ! -L /var/www/html/public/storage ]; then \
        php artisan storage:link; \
    fi


