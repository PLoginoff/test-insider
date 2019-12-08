# requirements
 - pdo_sqlite
 - composer
 - symfony (optional)

# install and run
 - `composer install`
 - `bin/console doctrine:database:create`
 - `bin/console doctrine:schema:create`
 - `bin/console doctrine:fixtures:load -n`
 - `symfony serve` and go to http://127.0.0.1:8000
 - or `php -S localhost:8000 -t public/` if you don't have `symfony`
