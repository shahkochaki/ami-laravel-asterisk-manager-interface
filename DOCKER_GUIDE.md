# Docker Installation Guide - v2.2.5

## مشکل Patch در Docker

اگر این خطا را می‌بینید:
```
No available patcher was able to apply patch patches/fix-console-command.patch to shahkochaki/ami-laravel-asterisk-manager-interface
```

## راه حل‌های قطعی:

### 1. استفاده از .dockerignore (توصیه شده)

ایجاد فایل `.dockerignore` در root پروژه:

```
vendor/
composer.lock
.git/
.env
node_modules/
*.patch
patches/
.composer/
```

### 2. Dockerfile بهینه

```dockerfile
FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files
COPY composer.json composer.json

# Install dependencies with optimized flags
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --no-cache --no-plugins

# Copy application
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www
```

### 3. دستور بهینه Composer در Docker

```dockerfile
# بهترین روش - بدون cache و plugins
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-cache --no-plugins

# یا با ignore platform requirements
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-req=ext-zip

# یا با حذف patches
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts
```

### 4. Multi-stage Docker Build

```dockerfile
# Build stage
FROM composer:2 as build
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --no-cache

# Production stage  
FROM php:8.1-fpm
WORKDIR /var/www
COPY --from=build /app/vendor ./vendor
COPY . .
```

### 5. حذف کامل Cache

```bash
# در محیط development
composer clear-cache
rm -rf vendor/ composer.lock
composer install --no-cache

# در Docker
RUN composer clear-cache && \
    composer install --no-interaction --prefer-dist --optimize-autoloader --no-cache
```

### 6. استفاده از Docker Compose

```yaml
version: '3.8'
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    volumes:
      - .:/var/www
      - /var/www/vendor  # Exclude vendor from host volume
    environment:
      - COMPOSER_CACHE_DIR=/tmp/composer-cache
```

## تست موفقیت آمیز

پس از اعمال راه حل‌ها:

```bash
# تست local
composer require shahkochaki/ami-laravel-asterisk-manager-interface:^2.2.5
php artisan ami:action Ping

# تست در Docker
docker build -t my-app .
docker run my-app php artisan ami:action Ping
```

## نکات مهم:

1. **همیشه از .dockerignore استفاده کنید**
2. **vendor را در Docker volume exclude کنید**
3. **از --no-cache flag استفاده کنید**
4. **patch های قدیمی را ignore کنید**

نسخه v2.2.5 این مشکلات را حل کرده و بهینه‌سازی‌های Docker دارد.