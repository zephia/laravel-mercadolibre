<?php
/*
 * This file is part of the Laravel Mercado Libre API client package.
 *
 * (c) Zephia <info@zephia.com.ar>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zephia\LaravelMercadoLibre\Provider;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use JMS\Serializer\SerializerBuilder;
use Zephia\MercadoLibre\Client\MercadoLibreClient;

/**
 * Class MercadoLibreServiceProvider
 *
 * @package Zephia\LaravelMercadoLibre\Provider
 */
class MercadoLibreServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $packageName = 'MercadoLibre';

    /**
     * Bootstrap the application service
     *
     * @return void
     */
    public function boot()
    {
        $this->setupRoutes($this->app->router);

        $this->publishes([
            __DIR__ . '/../../config/mercadolibre.php' => config_path('mercadolibre.php'),
        ], 'config');

        $this->app->bind('meli_api', function () {
            $serializer = SerializerBuilder::create()
                ->addMetadataDir(
                    base_path('vendor/zephia/mercadolibre/resources/config/serializer')
                )->build();
            return new MercadoLibreClient([], $serializer);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/mercadolibre.php', $this->packageName);
    }

    /**
     * Set up the application routes.
     *
     * @param Router $router
     *
     * @return void
     */
    public function setupRoutes(Router $router)
    {
        $router->group(['namespace' => 'Zephia\LaravelMercadoLibre\Http\Controllers'], function ($router) {
            include __DIR__ . '/../../routes/routes.php';
        });
    }
}