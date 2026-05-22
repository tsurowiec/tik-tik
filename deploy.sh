#!/usr/bin/env bash
set -e

git fetch origin
git reset --hard origin/main
npm run build
php artisan migrate --force
php artisan optimize
php artisan serve --host 0.0.0.0
