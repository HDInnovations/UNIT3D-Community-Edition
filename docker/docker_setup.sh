#!/usr/bin/env bash
if ! test -f "composer"; then
  ./composer-setup.sh
fi

php composer install --dev
#if ! test -f "vendor/autoload.php"; then
#  php composer install --no-dev --prefer-dist
#else
#  echo "Vendor already exists"
#fi

# Create a new key if we have not specified one
grep -q '^APP_KEY=$' .env || error_code=$?
if [[ "${error_code}" -eq 0 ]]; then
  echo "generating new key"
  php artisan key:generate
fi

# Assume that we have already seeded the data since we have migration rows
result=$(mysql unit3d -h mariadb -u unit3d -D unit3d -s -e "select count(*) from migrations;")
if [[ $result -eq "0" ]]; then
  php artisan migrate --seed  -n
else
  php artisan migrate  -n
fi

chown -R www-data:www-data /app
php artisan config:clear -n