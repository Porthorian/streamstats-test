FROM php:8.1-fpm-buster
RUN docker-php-ext-install pdo pdo_mysql
RUN pecl install redis && docker-php-ext-enable redis

CMD ["php-fpm"]