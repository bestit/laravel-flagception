<?php

namespace BestIt\LaravelFlagception;

use BestIt\LaravelFlagception\Directive\FlagceptionDirectiveProvider;
use BestIt\LaravelFlagception\Factory\ActivatorFactory;
use BestIt\LaravelFlagception\Factory\DecoratorFactory;
use Flagception\Manager\FeatureManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use BestIt\LaravelFlagception\Middleware\FlagceptionMiddleware;

/**
 * Class FlagceptionServiceProvider
 *
 * @author andre.varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\LaravelFlagception
 */
class FlagceptionServiceProvider extends ServiceProvider {

    /**
     * Boot the service. Register directive and middleware
     *
     * @param Router $router
     */
    public function boot(Router $router) {
        $router->aliasMiddleware('feature', FlagceptionMiddleware::class);
        $this->app->make(FlagceptionDirectiveProvider::class)->registerDirective();

        $this->publishes([
            __DIR__.'/../config/flagception.php' => $this->app->make('path.config') . '/flagception.php',
        ], 'flagception');
    }

    /**
     * Register the service
     */
    public function register() {
        $this->mergeConfigFrom( __DIR__.'/../config/flagception.php', 'flagception');

        $this->app->singleton(FeatureManager::class, function(Application $app) {
            return new FeatureManager(
                $app->make(ActivatorFactory::class)->create(),
                $app->make(DecoratorFactory::class)->create()
            );
        });
    }

    /**
     * Provide alias
     *
     * @return array
     */
    public function provides() {
        return ['Flagception'];
    }
}