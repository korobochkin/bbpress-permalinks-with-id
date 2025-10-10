#!/usr/bin/env bash

set -Eeuo pipefail

cd /var/www/html || exit 1

wp core install \
  --url=http://one.wordpress.test:8080 \
  --title="Test Site" \
  --admin_user=admin \
  --admin_password=12345 \
  --admin_email=admin@one.wordpress.test \
  --locale=en_US \
  --skip-email

wp plugin activate \
  bbpress

wp theme activate \
  twentytwelve
