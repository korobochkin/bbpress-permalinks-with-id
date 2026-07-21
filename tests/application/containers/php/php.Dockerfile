FROM php:8.5.8-cli-alpine AS php-base

SHELL ["/bin/sh", "-euo", "pipefail", "-c"]

RUN \
    --mount=type=cache,target=/var/cache/apk,sharing=locked \
    apk update --progress=no \
    && apk upgrade --progress=no \
    && apk add --progress=no make

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

FROM php-base-with-composer AS dependencies

USER $UID:$GID

RUN \
    --mount=type=bind,source=tests,destination=./tests \
    --mount=type=bind,source=composer.json,destination=./composer.json \
    --mount=type=bind,source=composer.lock,destination=./composer.lock \
    --mount=type=bind,source=Makefile,destination=./Makefile \
    --mount=type=cache,id=composer,mode=0755,uid=$UID,gid=$GID,destination=/home/php/.composer \
    make vendor

FROM php-base AS tests-runner

ENTRYPOINT ["make", "run_tests"]

COPY --from=dependencies /home/php/app/vendor /home/php/app/vendor

FROM php-base-with-composer AS develop

RUN \
    --mount=type=cache,target=/var/cache/apk,sharing=locked \
    apk add --progress=no autoconf build-base linux-headers \
    && pecl install xdebug

USER $UID:$GID
