# set up
```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migration --seed
php artisan cache:clear
php artisan config:clearg
```
