# library
```
composer install 
```

Внести дані доступу локальної MySQL в .env файл
```
php bin/console doctrine:database:create // Створює БД
```
```
php bin/console doctrine:migrations:migrate --no-interaction // Формування структури БД (завантажує міграцій)
```
```
symfony server:start
```
