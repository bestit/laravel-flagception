<?php

return [
    'features' => [
        'feature_123' => [
            'active' => false,
            'cookie' => true,
        ],
    ],
    'cookie' => [
        'active' => false,
        'name' => 'flagception',
        'delimiter' => ','
    ],
    'middleware' => [
        'enabled' => true,
    ],
    'context' => [

    ],
    'activators' => [
        \BestIt\LaravelFlagception\Activator\CookieActivator::class
    ],
    'decorators' => [
        \BestIt\LaravelFlagception\Decorator\ConfigContextDecorator::class
    ],
    'expressions' => [
        \Flagception\Constraint\Provider\DateProvider::class,
        \Flagception\Constraint\Provider\MatchProvider::class,
        \Flagception\Constraint\Provider\RatioProvider::class
    ],
];