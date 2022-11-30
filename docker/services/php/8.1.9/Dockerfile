FROM php:8.1.9-fpm-alpine

LABEL maintainer="Z-EV"

ARG XDEBUG_VERSION=3.1.3
ARG REDIS_VERSION=5.3.7

RUN apk update && apk upgrade && apk add \
    libjpeg-turbo-dev \
    oniguruma-dev \
    freetype-dev \
    libzip-dev \
    libpng-dev \
    supervisor \
    gettext \
    curl \
	bash \
    build-base \
    autoconf \
    tzdata \
    mc \
    postgresql-dev \
    libpq-dev \
    && rm -rf /var/cache/apk/*

RUN pecl install \
      xdebug-${XDEBUG_VERSION} \
      redis-${REDIS_VERSION} \
    && docker-php-ext-enable redis

RUN docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    pdo \
    pdo_pgsql \
    pgsql \
	zip \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) gd

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php \
	&& php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php

RUN addgroup -g 1000 dev
RUN adduser -u 1000 -G dev -s /bin/sh -D dev
