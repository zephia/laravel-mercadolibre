# Laravel MercadoLibre API Client
This package is a wrapper of [MercadoLibre API Client PHP Class](https://github.com/zephia/mercadolibre) for Laravel Framework.

## Installation

Using [composer](http://getcomposer.org)

Run the following command and provide the latest stable version:

```bash
composer require zephia/laravel-mercadolibre
```

Then register this service provider with Laravel in config/app.php:

```php
'providers' => [
    ...
    Zephia\LaravelMercadoLibre\MercadoLibreServiceProvider::class,
    ...
]
```

Publish config file:

```bash
php artisan vendor:publish --provider="Zephia\LaravelMercadoLibre\MercadoLibreServiceProvider" --tag="config"
```

Add **MERCADOLIBRE_APP_KEY** and **MERCADOLIBRE_APP_SECRET** constants to your .env file:

```
MERCADOLIBRE_APP_KEY=YOUR-MERCADOLIBRE-APP-KEY
MERCADOLIBRE_APP_SECRET=YOUR-MERCADOLIBRE-APP-SECRET
```

## Usage
### Get user data

See fields documentation at [official MercadoLibre API reference](http://developers.mercadolibre.com/api-docs/)

```php
<?php

/**
 * User Show
 */

$user = app('meli_api')->userShow('MLA123456789');

var_dump($user);

// object(Zephia\MercadoLibre\Entity\User)