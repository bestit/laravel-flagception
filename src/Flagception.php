<?php

namespace BestIt\LaravelFlagception;

use Illuminate\Support\Facades\Facade;

/**
 * Class Flagception
 *
 * @author andre.varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\LaravelFlagception
 */
class Flagception extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'Flagception';
    }
}