# Contributing

Below introduces & instructs you how to contribute to the project.

## Installing dependencies

```bash
    npm install
    composer install
```

## Initializing the project

You should config database connection in `.env` file.

```bash
    php -r "file_exists('.env') || copy('.env.example', '.env');"
    php artisan key:generate
    php artisan migrate
```

## Generating helper files for php IDE & typescript files

```bash
    php artisan ide-helper:generate
    php artisan ide-helper:meta
    php artisan ide-helper:models --nowrite
    php artisan typescript:transform
```

## To register a php type to generate typescript files

Firstly you Secondly you should
 add `@typescript` phpdoc to php type

```php
    /** @typescript */
    class FetchedCardType
    {
        //
    }
```

Secondly you should run special command to generate it to typescript

```bash
    php artisan typescript:transform
```
