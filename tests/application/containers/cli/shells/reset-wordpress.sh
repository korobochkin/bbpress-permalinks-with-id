#!/bin/bash

set -Eeuo pipefail

cd /var/www/html
echo "DELETE FROM wp_posts; DELETE FROM wp_postmeta;" | wp db cli --defaults
wp plugin deactivate bbpress-permalinks-with-id
wp rewrite flush
