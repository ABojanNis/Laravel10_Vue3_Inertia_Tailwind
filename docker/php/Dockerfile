FROM php:8.1-fpm-alpine

# Arguments defined in docker-compose.yml
ARG user
ARG group

# Install system dependencies
RUN apk --update add bash \
    wget \
    curl \
    git \
    php8 \
    php8-curl \
    php8-openssl \
    php8-iconv \
    php8-json \
    php8-mbstring \
    php8-phar \
    php8-dom --repository http://nl.alpinelinux.org/alpine/edge/testing/ && rm /var/cache/apk/*

# Install nodejs
RUN apk add nodejs npm

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN adduser $user -D $group && mkdir -p /etc/sudoers.d \
    && echo "$user ALL=(ALL) NOPASSWD: ALL" > /etc/sudoers.d/$user \
    && chmod 0440 /etc/sudoers.d/$user

# Set working directory
WORKDIR /var/www/html

USER $user
