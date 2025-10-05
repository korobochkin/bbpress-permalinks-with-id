FROM php:8.4-cli-bookworm

COPY --from=composer/composer:2.8-bin /composer /usr/bin/composer

RUN apt update --quiet --assume-yes \
    && apt install unzip --quiet --assume-yes --no-install-recommends --no-install-suggests

WORKDIR /root/tests-application
