<?php

namespace BestIt\LaravelFlagception;

use Flagception\Manager\FeatureManager;
use Illuminate\Support\Facades\Facade;

/**
 * The flagception facade.
 *
 * @author André Varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\LaravelFlagception
 *
 * @method static bool isActive($feature) Check if an feature is active.
 */
class Flagception extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return FeatureManager::class;
    }
}
