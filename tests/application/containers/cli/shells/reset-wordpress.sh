#!/bin/bash

set -Eeuo pipefail

cd /var/www/html
wp db query "DELETE FROM wp_posts;"
wp plugin deactivate bbpress-permalinks-with-id
wp rewrite flush
