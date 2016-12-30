@echo off

cd ..

php bin/console cache:clear -e prod
php bin/console assets:install -e prod

pause