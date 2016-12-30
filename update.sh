#!/bin/bash

git pull

php ../composer/composer.phar install

php bin/console cache:clear -e prod
php bin/console assets:install -e prod
