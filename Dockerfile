# Build stage
FROM php:8.3-cli AS build
WORKDIR /app
RUN apt-get update && apt-get install -y git unzip libzip-dev \
 && docker-php-ext-install zip pdo_mysql
COPY . .
RUN php -r "copy('https://getcomposer.org/installer','composer-setup.php');" \
 && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
 && composer install --no-dev --optimize-autoloader --no-scripts

# Run stage
FROM php:8.3-cli
WORKDIR /app
RUN apt-get update && apt-get install -y libzip-dev \
 && docker-php-ext-install pdo_mysql
COPY --from=build /app /app
ENV APP_ENV=production
ENV PORT=8080
EXPOSE 8080
# 初回起動時にマイグレーション→内蔵サーバ起動（簡易）
CMD ["sh", "-c", "php artisan migrate --force && php -S 0.0.0.0:$PORT -t public"]

