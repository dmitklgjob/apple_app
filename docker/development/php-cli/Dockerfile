FROM php:8.1.9-cli-alpine3.15

ARG TIMEZONE="Europe/Moscow"
ARG XDEBUG_VERSION="3.1.3"
ARG EXT_MONGODB_VERSION="v1.14"

RUN apk update \
    && apk add --update --no-cache $PHPIZE_DEPS \
        bash \
        git \
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

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --version=2.2.5 --quiet \
  && rm -rf /root/.composer/cache

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini
COPY ./config/*.ini $PHP_INI_DIR/conf.d/

COPY scripts/wait-for-it.sh /usr/local/bin/wait-for-it
RUN dos2unix /usr/local/bin/wait-for-it && chmod +x /usr/local/bin/wait-for-it

RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app
WORKDIR /app
USER app
