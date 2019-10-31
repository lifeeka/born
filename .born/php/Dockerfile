
ARG PHP_VERSION
FROM php:${PHP_VERSION}-fpm
WORKDIR /app

ARG TZ
RUN echo -e "[0;32mTimezone set to $TZ[0m"

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN docker-php-ext-install pdo_mysql

EXPOSE 9000
    
    