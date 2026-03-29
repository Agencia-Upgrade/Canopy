# Canopy PHP 8.5 Dockerfile
# Agência Upgrade — https://agenciaupgrade.com.br

FROM php:8.5-fpm-bookworm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl git zip unzip \
    libpng-dev libjpeg-dev libwebp-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev libicu-dev libssl-dev \
    libmagickwand-dev \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install -j$(nproc) \
    gd \
    mysqli \
    pdo \
    pdo_mysql \
    bcmath \
    zip \
    intl \
    exif \
    mbstring \
    xml \
    soap

# Install pecl extensions
RUN pecl install imagick redis && \
    docker-php-ext-enable imagick redis

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install WP-CLI
RUN curl -fsSL -o /usr/local/bin/wp \
    https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && \
    chmod +x /usr/local/bin/wp

WORKDIR /var/www/html

EXPOSE 9000
