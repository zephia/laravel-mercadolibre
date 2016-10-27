<?php

/*
 * This file is part of the Laravel Mercado Libre API client package.
 *
 * (c) Zephia <info@zephia.com.ar>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | App Key
    |--------------------------------------------------------------------------
    |
    | This your app key obtained at http://applications.mercadolibre.com
    |
    | Default: null
    |
    */

    'app_key' => env('MERCADOLIBRE_APP_KEY'),

    /*
    |--------------------------------------------------------------------------
    | App Secret
    |--------------------------------------------------------------------------
    |
    | This your app key obtained at http://applications.mercadolibre.com
    |
    | Default: null
    |
    */

    'app_secret' => env('MERCADOLIBRE_APP_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Callback route controller
    |--------------------------------------------------------------------------
    |
    | Defines the route controller class which handles the callback url
    |
    | Default: '\Zephia\LaravelMercadoLibre\Http\Controllers\CallbackController'
    |
    */

    'callback_controller' => '\Zephia\LaravelMercadoLibre\Http\Controllers\CallbackController',
];
