@echo off

cd ..

php bin/console cache:clear -e dev
php bin/console assets:install -e dev

pause