#!/bin/sh
php artisan optimize
php artisan icons:cache
php artisan storage:link
