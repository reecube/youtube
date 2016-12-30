#!/bin/bash

git pull

php ../composer/composer.phar update

php bin/console cache:clear -e prod
php bin/console assets:install -e prod
