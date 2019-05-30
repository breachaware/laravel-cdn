<?php

namespace BreachAware\LaravelCdn;

use Illuminate\Support\ServiceProvider;

/**
 * Class CdnServiceProvider.
 *
 * @category Service Provider
 *
 * @author  Mahmoud Zalt <mahmoud@vinelab.com>
 * @author  Abed Halawi <abed.halawi@vinelab.com>
 */
class CdnServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/cdn.php' => config_path('cdn.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        // implementation bindings:
        //-------------------------
        $this->app->bind(
            'BreachAware\LaravelCdn\Contracts\CdnInterface',
            'BreachAware\LaravelCdn\Cdn'
        );

        $this->app->bind(
            'BreachAware\LaravelCdn\Providers\Contracts\ProviderInterface',
            'BreachAware\LaravelCdn\Providers\AwsS3Provider'
        );

        $this->app->bind(
            'BreachAware\LaravelCdn\Contracts\AssetInterface',
            'BreachAware\LaravelCdn\Asset'
        );

        $this->app->bind(
            'BreachAware\LaravelCdn\Contracts\FinderInterface',
            'BreachAware\LaravelCdn\Finder'
        );

        $this->app->bind(
            'BreachAware\LaravelCdn\Contracts\ProviderFactoryInterface',
            'BreachAware\LaravelCdn\ProviderFactory'
        );

        $this->app->bind(
            'BreachAware\LaravelCdn\Contracts\CdnFacadeInterface',
            'BreachAware\LaravelCdn\CdnFacade'
        );

        $this->app->bind(
            'BreachAware\LaravelCdn\Contracts\CdnHelperInterface',
            'BreachAware\LaravelCdn\CdnHelper'
        );

        $this->app->bind(
            'BreachAware\LaravelCdn\Validators\Contracts\ProviderValidatorInterface',
            'BreachAware\LaravelCdn\Validators\ProviderValidator'
        );

        $this->app->bind(
            'BreachAware\LaravelCdn\Validators\Contracts\CdnFacadeValidatorInterface',
            'BreachAware\LaravelCdn\Validators\CdnFacadeValidator'
        );

        $this->app->bind(
            'BreachAware\LaravelCdn\Validators\Contracts\ValidatorInterface',
            'BreachAware\LaravelCdn\Validators\Validator'
        );

        // register the commands:
        //-----------------------
        $this->app->singleton('cdn.push', function ($app) {
            return $app->make('BreachAware\LaravelCdn\Commands\PushCommand');
        });

        $this->commands('cdn.push');

        $this->app->singleton('cdn.empty', function ($app) {
            return $app->make('BreachAware\LaravelCdn\Commands\EmptyCommand');
        });

        $this->commands('cdn.empty');

        // facade bindings:
        //-----------------

        // Register 'CdnFacade' instance container to our CdnFacade object
        $this->app->singleton('CDN', function ($app) {
            return $app->make('BreachAware\LaravelCdn\CdnFacade');
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
