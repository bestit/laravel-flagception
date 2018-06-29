<?php

namespace BestIt\LaravelFlagception\Factory;

use Flagception\Activator\ArrayActivator;
use Flagception\Activator\ChainActivator;
use Flagception\Activator\ConstraintActivator;
use Flagception\Constraint\ConstraintResolver;
use Flagception\Factory\ExpressionLanguageFactory;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Foundation\Application as App;

/**
 * Factory for the composite of all activators.
 *
 * @author André Varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\LaravelFlagception\Factory
 */
class ActivatorCompositeFactory
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
     * ActivatorCompositeFactory constructor.
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
     * Gather all features in activators activator.
     *
     * @return ChainActivator
     */
    public function create()
    {
        $chainActivator = new ChainActivator();
        $chainActivator->add($this->getArrayActivator());
        $chainActivator->add($this->getConstraintActivator());

        foreach ($this->config->get('flagception.activators') as $activator) {
            $chainActivator->add($this->app->make($activator));
        }

        return $chainActivator;
    }

    /**
     * Builds the array activator from the config.
     *
     * @return ArrayActivator
     */
    private function getArrayActivator()
    {
        $activeFeatures = [];

        foreach ($this->config->get('flagception.features') as $name => $settings) {
            if (isset($settings['active']) && $settings['active']) {
                $activeFeatures[] = $name;
            }
        }

        return new ArrayActivator($activeFeatures);
    }

    /**
     * Create the ConstraintActivator from the config.
     *
     * @return ConstraintActivator
     */
    private function getConstraintActivator()
    {
        $constraints = [];

        foreach ($this->config->get('flagception.features') as $name => $settings) {
            if (isset($settings['constraint']) && $settings['constraint']) {
                $constraints[$name] = $settings['constraint'];
            }
        }

        $factory = new ExpressionLanguageFactory();

        foreach ($this->config->get('flagception.expressions') as $provider) {
            $factory->addProvider($this->app->make($provider));
        }

        return new ConstraintActivator(new ConstraintResolver($factory->create()), $constraints);
    }
}
