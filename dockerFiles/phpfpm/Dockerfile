FROM bitnami/php-fpm:latest

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y netcat

RUN apt-get update && apt-get install -y \
    autoconf \
    php-dev \
    libtool \
    && pecl install redis

# Create a new ini file with the extension enabled
RUN echo "extension=redis.so" >> /opt/bitnami/php/etc/conf.d/redis.ini

WORKDIR /api

COPY  . .

RUN composer install

# Make the start script executable
COPY start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]