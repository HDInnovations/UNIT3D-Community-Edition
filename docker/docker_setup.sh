#!/usr/bin/env bash
# This file is executed inside the php container to configure it

mkdir -p storage/logs \
  storage/framework/sessions \
  storage/framework/views \
  storage/framework/cache \
  storage/framework/testing \
  storage/app/public \
  bootstrap/cache \
  public/files/img \
  public/files/subtitles \
  public/files/tmp \
  public/files/tmpzip \
  public/files/torrents

chmod 777 storage/logs \
  storage/framework/sessions \
  storage/framework/views \
  storage/framework/cache \
  storage/framework/testing \
  storage/app/public \
  bootstrap/cache \
  storage/framework \
  public/files/img \
  public/files/subtitles \
  public/files/tmp \
  public/files/tmpzip \
  public/files/torrents

if ! test -f "composer"; then
  ./composer-setup.sh
fi

php -d memory_limit=-1 composer install

# Create a new key if we have not specified one
grep -q '^APP_KEY=$' .env || error_code=$?
if [[ "${error_code}" -eq 0 ]]; then
  echo "Generating new key"
  php artisan key:generate
fi

# Assume that we have already seeded the data since we have migration rows
result=$(mysql unit3d -h mariadb -u unit3d -punit3d -D unit3d -s -e "select count(*) from migrations;")
if [[ $result -eq "0" ]]; then
  php artisan migrate --seed --force -n
else
  php artisan migrate --force -n
fi

php artisan config:clear -n

php-fpm