# Angularavel - Laravel 5.5 with Angular 5 (Boiler Plate)

## Installation:

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

## To include component template to the component use following code:
```ts
'template': require('./app.component.html'),
```


## To include component style to the component use following code:
```ts
'styles': [`${require('./app.component.scss')}`]
```
