# Canopy PHP 8.5 Dockerfile
# Agência Upgrade — https://agenciaupgrade.com.br

FROM php:8.5-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    curl \
    git \
    zip \
    unzip \
    mysql-client \
    imagemagick \
    imagemagick-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    freetype-dev \
    redis

# Install PHP extensions
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp \
    --with-xpm && \
    docker-php-ext-install -j$(nproc) \
    gd \
    mysqli \
    pdo_mysql \
    opcache \
    bcmath

# Install pecl extensions
RUN pecl install imagick redis && \
    docker-php-ext-enable imagick redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install WP-CLI
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && \
    chmod +x wp-cli.phar && \
    mv wp-cli.phar /usr/local/bin/wp

WORKDIR /var/www/html

EXPOSE 9000
