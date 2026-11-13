#!/bin/bash

set -Eeuo pipefail

cd /var/www/html
echo "DELETE FROM wp_posts; DELETE FROM wp_postmeta; DELETE FROM wp_usermeta WHERE meta_key LIKE 'wp__bbp%'" | wp db cli --defaults
wp plugin deactivate bbpress-permalinks-with-id
wp rewrite flush
