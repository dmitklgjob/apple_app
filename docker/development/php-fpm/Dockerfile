FROM php:8.1.9-fpm-alpine3.15

ARG TIMEZONE="Europe/Moscow"
ARG EXT_MONGODB_VERSION="v1.14"

RUN apk update \
    && apk add --update --no-cache $PHPIZE_DEPS \
        postgresql-dev \
        libzip-dev \
        icu \
        icu-dev \
        gnu-libiconv \
        freetype-dev \
        libjpeg-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pgsql pdo_pgsql zip bcmath intl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
	&& rm -rf /tmp/* /var/cache/apk/*

ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
     && printf "[PHP]\ndate.timezone = '%s'\n", ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
     && "date"

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini
COPY ./config/*.ini $PHP_INI_DIR/conf.d/

WORKDIR /app
