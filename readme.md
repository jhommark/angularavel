# Angularavel - Laravel 5.5 with Angular 5 (Boiler Plate)

## Features:
- JWT Authentication
- Role-based Permissions

## Installation

```
Replicate `.env.example` to `.env` file and configure everything you need especially the database
```
```
composer install
npm install
php artisan key:generate
php artisan jwt:secret
php artisan migrate
php artisan db:seed
```

## Development

```
npm run dev
```

## Watch for changes

```
npm run watch
```

## Hot Reload

```
npm run hot
```

## Serving the Application

```
php artisan serve
```

## To include component template, use the following code:
```ts
'template': require('./app.component.html'),
```

## To include component style, use the following code:
```ts
'styles': [`${require('./app.component.scss')}`]
```

## Credits and References:
- <a href="https://github.com/christiannwamba/scotch-entrust">https://github.com/christiannwamba/scotch-entrust</a>
- <a href="https://github.com/ehsanhasani/laravel-5-angular-4">https://github.com/ehsanhasani/laravel-5-angular-4</a>
- <a href="https://github.com/tymondesigns/jwt-auth">https://github.com/tymondesigns/jwt-auth</a>
- <a href="https://github.com/Zizaco/entrust">https://github.com/Zizaco/entrust</a>
