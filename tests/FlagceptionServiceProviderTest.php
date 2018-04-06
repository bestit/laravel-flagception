<?php

namespace BestIt\LaravelFlagception\Tests;

use BestIt\LaravelFlagception\Directive\FlagceptionDirectiveProvider;
use BestIt\LaravelFlagception\FlagceptionServiceProvider;
use BestIt\LaravelFlagception\Middleware\FlagceptionMiddleware;
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Routing\Router;
use PHPUnit\Framework\TestCase;

/**
 * Class FlagceptionServiceProviderTest
 *
 * @author andre.varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\LaravelFlagception\Tests
 */
class FlagceptionServiceProviderTest extends TestCase
{
    /**
     * @var FlagceptionServiceProvider $fixture
     */
    private $fixture;

    /**
     * @var App $app
     */
    private $app;

    /**
     * @throws \ReflectionException
     */
    public function setUp()
    {
        $this->fixture = $fixture = new FlagceptionServiceProvider(
            $this->app = $this->createMock(App::class)
        );
    }

    /**
     * Test the boot method
     *
     * @throws \ReflectionException
     * @return void
     */
    public function testBoot()
    {
        $router = $this->createMock(Router::class);
        $router
            ->expects(static::once())
            ->method('aliasMiddleware')
            ->with('feature', FlagceptionMiddleware::class);

        $this->app
            ->expects(static::at(0))
            ->method('make')
            ->with(FlagceptionDirectiveProvider::class)
            ->willReturn($directiveProvider = $this->createMock(FlagceptionDirectiveProvider::class));
        $this->app
            ->expects(static::at(1))
            ->method('make')
            ->with('path.config')
            ->willReturn('');

        $directiveProvider
            ->expects(static::once())
            ->method('registerDirective');

        $this->fixture->boot($router);
    }
}