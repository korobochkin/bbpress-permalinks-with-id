FROM php:8.5-cli AS php-base

ARG UID
ARG GID

RUN addgroup --gid=$GID php \
    && adduser --uid=$UID --gid=$GID --shell=/bin/bash --comment "" --disabled-password php

USER $UID:$GID

WORKDIR /home/php/app

FROM php-base AS php-base-with-composer

USER root

RUN \
    --mount=type=cache,target=/var/cache/apt,sharing=locked \
    --mount=type=cache,target=/var/lib/apt/lists,sharing=locked \
    apt-get update --quiet --assume-yes \
    && apt-get install --quiet --assume-yes --no-install-recommends --no-install-suggests unzip

COPY --from=composer/composer:2.9-bin --chmod=0555 /composer /usr/bin/composer

FROM php-base-with-composer AS develop

USER $UID:$GID
