# Stage 1: Build assets
FROM node:20-alpine AS build-stage
WORKDIR /app
COPY package*.json ./
RUN npm ci || npm install
COPY . .
RUN npm run build

# Stage 2: App production
FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libwebp-dev \
    zip \
    libzip-dev \
    unzip \
    git \
    curl \
    icu-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd pdo pdo_mysql zip bcmath intl exif pcntl opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files for caching
COPY composer.json composer.lock ./

# Install dependencies before copying all files
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy application files
COPY . .

# Copy built assets from build-stage
COPY --from=build-stage /app/public/build ./public/build

# Finish composer setup
RUN composer install --no-dev --optimize-autoloader

# PHP config
COPY deploy/docker/php/prod.ini /usr/local/etc/php/conf.d/prod.ini

# Nginx config
COPY deploy/docker/nginx/app.conf /etc/nginx/http.d/default.conf

# Entrypoint script
COPY deploy/docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Permissions
RUN chown -R www-data:www-data /var/www

EXPOSE 80

ENTRYPOINT ["entrypoint.sh"]
