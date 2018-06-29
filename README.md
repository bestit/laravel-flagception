# laravel-flagception
Feature toggle for laravel. Laravel provider for the flagception/flagception-sdk.

## Installation and setup
Install it via composer:
``` bash
composer require bestit/laravel-flagception
```
The package will automatically register itself.

If you are using Laravel 5.5 or higher this package will be auto-discovered.
For earlier versions you have to register the service provider in your app.php:

``` php
// config/app.php

'providers' => [
    // ...
    BestIt\LaravelFlagception\FlagceptionServiceProvider::class,
];
```

The core of this package is the config. To publish it run:

``` php
php artisan vendor:publish --provider="BestIt\LaravelFlagception\FlagceptionServiceProvider"
```

## Basic usage

Define a feature in your config file:

``` php
// config/flagception.php

return [
    //...
    'features' => [
        'feature_123' => [
            'active' => false,
        ],
    ],
];
```

Inject the FeatureManager where you need it:
``` php
class WelcomeController extends Controller
{
    public function index(FeatureManager $featureManager)
    {
        if ($featureManager->isActive('feature_123')) {
            //do something
        }
        //...
    }
}
```