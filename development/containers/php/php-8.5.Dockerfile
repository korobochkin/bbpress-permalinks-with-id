FROM php:8.5.9-cli-alpine AS php-base

SHELL ["/bin/sh", "-euo", "pipefail", "-c"]

RUN \
   mv /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

RUN \
    --mount=type=cache,target=/var/cache/apk,sharing=locked \
    apk update --no-progress \
    && apk upgrade --no-progress \
    && apk add --no-progress make

ARG UID
ARG GID

RUN \
   addgroup -g $GID php \
   && adduser -u $UID -G php -D -s /bin/sh php

USER $UID:$GID

WORKDIR /home/php/app

FROM php-base AS php-base-with-composer

USER root

RUN \
    --mount=type=cache,target=/var/cache/apk,sharing=locked \
    apk add --progress=no unzip

COPY --from=composer/composer:2.10-bin --chmod=0555 /composer /usr/bin/composer

FROM php-base-with-composer AS phpcs

USER $UID:$GID

FROM php-base AS default