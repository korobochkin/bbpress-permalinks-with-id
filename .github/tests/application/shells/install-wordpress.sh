#!/usr/bin/env bash

set -Eeuo pipefail

cd /var/www/html || exit 1

if ! wp core is-installed 2>/dev/null; then
  wp core install \
    --url="${TEST_SITE_HOME}" \
    --title="${TEST_SITE_TITLE}" \
    --admin_user="${TEST_SITE_ADMIN_LOGIN}" \
    --admin_password="${TEST_SITE_ADMIN_PASSWORD}" \
    --admin_email="${TEST_SITE_ADMIN_EMAIL}" \
    --locale="${TEST_SITE_LOCALE}" \
    --skip-email
fi

if ! wp plugin is-active bbpress 2>/dev/null; then
  wp plugin activate \
    bbpress
fi

if ! wp theme is-active twentytwelve 2>/dev/null; then
  wp theme activate \
    twentytwelve
fi
