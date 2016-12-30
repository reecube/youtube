@echo off

cd ..

php bin/console cache:clear -e prod

pause