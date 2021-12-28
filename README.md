# Jetstream Laravel + Inertiajs + Vite + typescript

A template for above powerful technique


## Install dependencies

```bash
    npm install
    composer install
```

## Generate helper files for php IDE

Special command `php artisan ide-helper:models --nowrite` require you connected database

```bash
    php artisan ide-helper:generate
    php artisan ide-helper:meta
    php artisan ide-helper:models --nowrite
```

## Lint typescript files of vuejs

A lint command for check issue typescript vue files. This command is powerful but used too much resource pc then I not add it to lint-staged and if add to lint-staged it not work correctly (Currently I don't know)

```bash
    npm run lint:vue
```

## Handling 

Recommend actions for you when a case occurred

### Updated Models

You should fresh `ide model helper file` and fresh `declared model typescript file` (both require connected database)

```bash
    php artisan ide-helper:models --nowrite
    php artisan typescript:generate
```
