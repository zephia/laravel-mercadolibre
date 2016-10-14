<?php

namespace Zephia\LaravelMercadoLibre\Provider;

use Illuminate\Support\ServiceProvider;
use JMS\Serializer\SerializerBuilder;
use Zephia\MercadoLibre\Client\MercadoLibreClient;

class MercadoLibreServiceProvider extends ServiceProvider
{
    protected $packageName = '';

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
