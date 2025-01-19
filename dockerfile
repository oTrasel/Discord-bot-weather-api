
FROM php:8.3-cli


RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libcurl4-openssl-dev \
    && docker-php-ext-install curl \
    && apt-get clean


COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


WORKDIR /app


COPY . .


RUN composer install --no-dev --optimize-autoloader


EXPOSE 8080


CMD ["php", "bot.php"]
