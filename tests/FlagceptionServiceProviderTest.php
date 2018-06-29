<?php

namespace BestIt\LaravelFlagception\Tests;

use BestIt\LaravelFlagception\Directive\FlagceptionDirectiveProvider;
use BestIt\LaravelFlagception\FlagceptionServiceProvider;
use BestIt\LaravelFlagception\Middleware\FlagceptionMiddleware;
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Routing\Router;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class FlagceptionServiceProviderTest
 *
 * @author André Varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\LaravelFlagception\Tests
 */
class FlagceptionServiceProviderTest extends TestCase
{
    /**
     * The flagception service provider.
     *
     * @var FlagceptionServiceProvider $fixture
     */
    private $fixture;

    /**
     * The application container.
     *
     * @var App|PHPUnit_Framework_MockObject_MockObject $app
     */
    private $app;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = $fixture = new FlagceptionServiceProvider(
            $this->app = $this->createMock(App::class)
        );
    }

    /**
     * Test the boot method.
     *
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