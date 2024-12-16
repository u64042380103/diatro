# laravel_dev

## Getting started
```
cp .env.example .env
composer install
npm install
npm run build
```

## Make Auth
- set `.env` file with database information.
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_dev
DB_USERNAME=root
DB_PASSWORD=
```

- create database with "DB_USERNAME=" from `.env` file.
- then run the following command.
```
php artisan migrate
php artisan db:seed
```

## Contact
- 086-390-3930 [OFF]