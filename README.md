# Welove Test

How to run:

- Import sql file to your database
- Copy `.env.example` to `.env`
    - If database settings in the file are wrong, change them
- Run the following commands:
```
composer install
php artisan key:generate
php artisan serve
```

The server now should be running at http://127.0.0.1:8000
