FROM php:8.3-fpm

# Set the working directory
WORKDIR /var/www/html

#TODO
# Copy static ffmpeg binary into container
COPY ./services/ffmpeg/bin/ffmpeg /usr/local/bin/ffmpeg
COPY ./services/ffmpeg/bin/ffprobe /usr/local/bin/ffprobe

# Make them executable
RUN chmod +x /usr/local/bin/ffmpeg /usr/local/bin/ffprobe

# Install dependencies and PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends --fix-missing \
      software-properties-common \
      apt-utils \
      libpq-dev \
      libpng-dev \
      openssl \
      libjpeg-dev \
      libfreetype6-dev \
      libwebp-dev \
      libxpm-dev \
      libgd-dev \
      libzip-dev \
      libicu-dev \
      zip unzip git \
      libonig-dev \
      libxml2-dev \
      libsqlite3-dev \
      libssl-dev \
      curl \
      ffmpeg \
      supervisor \
      && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
      && docker-php-ext-install gd \
      && docker-php-ext-enable gd \
      && docker-php-ext-install intl mbstring pdo_mysql mysqli bcmath pcntl zip opcache \
      && docker-php-ext-enable pdo_mysql mysqli intl mbstring pcntl zip opcache \
      && pecl install redis-5.3.7 \
      && docker-php-ext-enable redis

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest

# Copy Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install Composer manually as a fallback
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin

COPY ./docker/colibriplus/php.ini /usr/local/etc/php/conf.d/php.ini
COPY ./docker/colibriplus/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

ARG UID=1000
ARG GID=1000

RUN groupadd -g $GID sail && \
    useradd -m -u $UID -g sail sail

USER sail







# Assuming PROJECT_DIR is /var/www/html
ENV PROJECT_PATH=/var/www/html

# Copy Supervisor config and replace placeholder
COPY deploy/supervisor/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf
RUN sed -i "s|{{PROJECT_PATH}}|$PROJECT_PATH|g" /etc/supervisor/conf.d/laravel-worker.conf


# Create log dir
RUN mkdir -p /var/www/html/storage/logs

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisord.conf"]
