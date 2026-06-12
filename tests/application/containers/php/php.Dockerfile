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

COPY --from=composer/composer:2.10-bin --chmod=0555 /composer /usr/bin/composer

USER $UID:$GID

FROM php-base-with-composer AS dependencies

RUN \
    --mount=type=bind,source=tests,destination=./tests \
    --mount=type=bind,source=composer.json,destination=./composer.json \
    --mount=type=bind,source=composer.lock,destination=./composer.lock \
    --mount=type=bind,source=Makefile,destination=./Makefile \
    --mount=type=cache,id=composer,destination=/home/php/.composer \
    make vendor

FROM php-base AS tests-runner

ENTRYPOINT ["make", "run_tests"]

COPY --from=dependencies /home/php/app/vendor /home/php/app/vendor

FROM php-base-with-composer AS develop

USER root

RUN pecl install xdebug

USER $UID:$GID
