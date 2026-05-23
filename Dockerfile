FROM php:8.5-fpm

WORKDIR /usr/src/app

RUN apt-get update && apt-get install -y --no-install-recommends \
        acl \
        file \
        gettext \
        git \
        nginx \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sSLf -o \
        /usr/local/bin/install-php-extensions \
        https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions \
    && chmod +x /usr/local/bin/install-php-extensions

RUN install-php-extensions \
        @composer \
        zip

ENV COMPOSER_ALLOW_SUPERUSER=1

# Configuration files
COPY --link conf/php-fpm.conf /usr/local/etc/php-fpm.conf
COPY --link conf/php.ini /usr/local/etc/php/conf.d/production.ini
COPY --link conf/nginx.conf /etc/nginx/nginx.conf
COPY --link --chmod=755 conf/start.sh /start.sh

RUN mkdir -p -m 777 /var/run/php

WORKDIR /usr/src/app

RUN mkdir -p -m 777 var/cache var/log

CMD ["/start.sh"]
