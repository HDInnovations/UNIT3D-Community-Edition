#!/usr/bin/env bash
if ! test -f "composer"; then
  ./composer-setup.sh
fi

if ! test -f "vendor/autoload.php"; then
  php composer install --no-dev --prefer-dist
else
  echo "Vendor already exists"
fi

grep -q '^APP_KEY=$' .env || error_code=$?
if [[ "${error_code}" -eq 0 ]]; then
  echo "generating key"
  php artisan key:generate
else
  echo "Key exists"
fi

php artisan migrate --seed