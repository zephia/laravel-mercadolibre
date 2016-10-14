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
use JMS\Serializer\SerializerBuilder;
use Zephia\MercadoLibre\Client\MercadoLibreClient;

/**
 * Class MercadoLibreServiceProvider
 *
 * @package Zephia\LaravelMercadoLibre\Provider
 * @author  Mauro Moreno <moreno.mauro.emanuel@gmail.com>
 */
class MercadoLibreServiceProvider extends ServiceProvider
{
    protected $packageName = 'MercadoLibre';

    /**
     * Bootstrap the application service
     *
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/mercadolibre.php' => config_path('mercadolibre.php'),
        ], 'config');

        $this->app->bind('ml_api', function() {
            $serializer = SerializerBuilder::create()
                ->addMetadataDir(
                    base_path('vendor/zephia/mercadolibre/resources/config/serializer')
                )->build();
            return new MercadoLibreClient([], $serializer);
        });
    }
}
