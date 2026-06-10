FROM php:8.5-cli-bookworm AS base

COPY --from=composer/composer:2.10-bin /composer /usr/bin/composer

RUN apt-get update --quiet --assume-yes \
    && apt-get install --quiet --assume-yes --no-install-recommends --no-install-suggests unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /root/tests-application

FROM base AS dependencies

RUN \
    --mount=type=bind,source=tests,destination=./tests \
    --mount=type=bind,source=composer.json,destination=./composer.json \
    --mount=type=bind,source=composer.lock,destination=./composer.lock \
    --mount=type=bind,source=Makefile,destination=./Makefile \
    --mount=type=cache,id=composer,destination=/root/.composer \
    make vendor

FROM php:8.5-cli-bookworm AS tests-runner

WORKDIR /root/tests-application

ENTRYPOINT ["make", "run_tests"]

COPY --from=dependencies /root/tests-application/vendor /root/tests-application/vendor

FROM base AS develop

RUN pecl install xdebug
