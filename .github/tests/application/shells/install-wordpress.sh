#!/usr/bin/env bash

set -Eeuo pipefail

cd /var/www/html || exit 1

wp core install \
  --url="${TEST_SITE_HOME}" \
  --title="${TEST_SITE_TITLE}" \
  --admin_user="${TEST_SITE_ADMIN_LOGIN}" \
  --admin_password="${TEST_SITE_ADMIN_PASSWORD}" \
  --admin_email="${TEST_SITE_ADMIN_EMAIL}" \
  --locale="${TEST_SITE_LOCALE}" \
  --skip-email

wp plugin activate \
  bbpress

wp theme activate \
  twentytwelve
