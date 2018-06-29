<?php

namespace BestIt\LaravelFlagception\Factory;

use Flagception\Decorator\ChainDecorator;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Foundation\Application as App;

/**
 * Factory for the decorator composite.
 *
 * @author André Varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\LaravelFlagception\Factory
 */
class DecoratorCompositeFactory
{
    /**
     * The laravel config facade.
     *
     * @var Config $config
     */
    private $config;

    /**
     * The laravel application container.
     *
     * @var App $app
     */
    private $app;

    /**
     * ActivatorFactory constructor.
     *
     * @param Config $config
     * @param App $app
     */
    public function __construct(Config $config, App $app)
    {
        $this->config = $config;
        $this->app = $app;
    }

    /**
     * Creates the chain decorator.
     *
     * @return ChainDecorator
     */
    public function create()
    {
        $chainDecorator = new ChainDecorator();

        foreach ($this->config->get('flagception.decorators') as $decorator) {
            $chainDecorator->add($this->app->make($decorator));
        }

        return $chainDecorator;
    }
}
