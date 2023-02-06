<?php
namespace Dodois;

use Dodois\Contracts\ClientContract;
use Dodois\Contracts\ConnectionContract;
use Dodois\Contracts\PublicApiContract;
use Illuminate\Support\ServiceProvider;

class DodoisServiceProvider extends ServiceProvider
{
    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [
        ClientContract::class => Client::class,
        PublicApiContract::class => PublicApi::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/dodois.php', 'dodois');

        $this->app->singleton(ConnectionContract::class, function () {
            return new Connection(
                config('dodois.connection.clientId'),
                config('dodois.connection.clientSecret'),
                config('dodois.connection.callbackUrl')
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            // Config
            __DIR__.'/../config/dodois.php' => config_path('dodois.php'),
        ], 'dodois');

        $this->loadRoutesFrom(__DIR__.'/../routes/dodois.php');
    }
}
