FROM php:8.4-cli

# install dependencies
RUN set -ex; \
    apt-get update && \
    apt-get -y --no-install-recommends install \
    $PHPIZE_DEPS \
	bash \
    git \
    unzip

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions \
    gd \
    intl \
    pdo_mysql \
    xdebug \
    zip

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"
COPY --from=composer/composer:2-bin --link /composer /usr/bin/composer

RUN addgroup --gid 1000 php
RUN adduser --ingroup php --shell /bin/sh php
USER php

RUN mkdir -p /home/php/.composer
ADD ./docker/dev-tools/composer.json /home/php/.composer/composer.json
RUN cd /home/php/.composer/ && composer update

WORKDIR /var/www/html