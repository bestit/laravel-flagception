<?php

namespace BestIt\LaravelFlagception\Tests;

use BestIt\LaravelFlagception\Flagception;
use Illuminate\Support\Facades\Facade;
use PHPUnit\Framework\TestCase;

/**
 * Class FlagceptionTest
 *
 * @author andre.varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\LaravelFlagception\Tests
 */
class FlagceptionTest extends TestCase
{
    /**
     * Test that the facade is extended
     */
    public function testIsFacade()
    {
        static::assertTrue(new Flagception() instanceof Facade);
    }
}