#!/bin/bash

# Wait for MySQL
until nc -z -v -w30 mysql 3306
do
  echo "Waiting for database connection..."
  # wait for 5 seconds before check again
  sleep 5
done

# Change to the /api directory
cd /api

# Run migrations
php migration migrate

# Start PHP-FPM
php-fpm -F -R
