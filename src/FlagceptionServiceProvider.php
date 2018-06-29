<?php

namespace BestIt\LaravelFlagception;

use BestIt\LaravelFlagception\Directive\FlagceptionDirectiveProvider;
use BestIt\LaravelFlagception\Factory\ActivatorCompositeFactory;
use BestIt\LaravelFlagception\Factory\DecoratorCompositeFactory;
use BestIt\LaravelFlagception\Middleware\FlagceptionMiddleware;
use Flagception\Manager\FeatureManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Contracts\Foundation\Application as App;

/**
 * Laravel service provider for flagception
 *
 * @author André Varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\LaravelFlagception
 */
class FlagceptionServiceProvider extends ServiceProvider
{
    /**
     * Boot the service. Register directive and middleware
     *
     * @param Router $router
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $router->aliasMiddleware('feature', FlagceptionMiddleware::class);
        $this->app->make(FlagceptionDirectiveProvider::class)->registerDirective();

        $this->publishes(
            [
                __DIR__.'/../config/flagception.php' => $this->app->make('path.config') . '/flagception.php'
            ],
            'flagception'
        );
    }

    /**
     * Register the service.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/flagception.php', 'flagception');

        $this->app->singleton(FeatureManager::class, function (App $app) {
            return new FeatureManager(
                $app->make(ActivatorCompositeFactory::class)->create(),
                $app->make(DecoratorCompositeFactory::class)->create()
            );
        });
    }

    /**
     * Provide alias.
     *
     * @return array
     */
    public function provides()
    {
        return ['Flagception'];
    }
}
