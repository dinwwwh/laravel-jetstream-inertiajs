# [Project Name]

Below introduces & instructs you about the project.

## Requirements

- **PHP 8.1+**
- **Nodejs 14+**
- **NPM & Composer**
- **common, curl, json, mbstring, mysql, xml, zip, openssl, mysqldump**

## Install dependencies

```bash
    npm install --production

    composer install --no-dev --no-interaction --no-plugins --optimize-autoloader
```

## Initializing the project

You should fill all the required information in `.env` file.

```bash
    php -r "file_exists('.env') || copy('.env.example', '.env');"
    php artisan key:generate
    php artisan migrate --seed
    npm run build
```

## Optimizing the project

```bash
    php artisan optimize
    php artisan view:cache
```

## Commands should be run when source code is changed

```bash
    php artisan optimize
    php artisan view:cache
    npm run build
    php artisan migrate --seed
```
