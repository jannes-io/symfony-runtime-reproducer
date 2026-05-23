#!/bin/sh
set -e

cd /usr/src/app

# Setup dirs/perms
## Copy default files if app is empty
if [ -z "$(ls -A /usr/src/app)" ]; then
  cp -r /usr/src/app.bak/* /usr/src/app
  chmod -R 777 /usr/src/app
fi

## Misc dirs
mkdir -p -m 777 var/cache var/log
mkdir -p -m 777 public/storage

# Install dependencies
composer install --no-progress --no-interaction

chmod +x -R bin

# Launch services
/usr/local/sbin/php-fpm -R -F --fpm-config /usr/local/etc/php-fpm.conf &
/usr/sbin/nginx -g "daemon off;"
