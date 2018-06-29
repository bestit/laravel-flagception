<?php

return [
    //Your feature flags.
    'features' => [
        'feature_123' => [
            'active' => false,
        ],
    ],
    //Define your cookie settings.
    'cookie' => [
        'active' => false,
        'name' => 'flagception',
        'delimiter' => ','
    ],
    //Is the route middleware enabled?
    'middleware' => [
        'enabled' => true,
    ],
    //Add your global context here.
    'context' => [],
    //Add your custom activators here.
    'activators' => [
        \BestIt\LaravelFlagception\Activator\CookieActivator::class
    ],
    //Add your custom decorators here.
    'decorators' => [
        \BestIt\LaravelFlagception\Decorator\ConfigContextDecorator::class
    ],
    //Add your expression language providers here.
    'expressions' => [
        \Flagception\Constraint\Provider\DateProvider::class,
        \Flagception\Constraint\Provider\MatchProvider::class,
        \Flagception\Constraint\Provider\RatioProvider::class
    ],
];
