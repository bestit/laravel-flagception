<?php

namespace BestIt\LaravelFlagception\Tests\Factory;

use BestIt\LaravelFlagception\Decorator\ConfigContextDecorator;
use Flagception\Decorator\ChainDecorator;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Foundation\Application as App;
use BestIt\LaravelFlagception\Factory\DecoratorFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class DecoratorFactoryTest
 *
 * @author andre.varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\LaravelFlagception\Tests\Factory
 */
class DecoratorFactoryTest extends TestCase
{
    /**
     * @var DecoratorFactory
     */
    private $fixture;

    /**
     * @var Config $config
     */
    private $config;

    /**
     * @var App $app
     */
    private $app;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->fixture = new DecoratorFactory(
            $this->config = $this->createMock(Config::class),
            $this->app = $this->createMock(App::class)
        );
    }

    /**
     * test the create method
     *
     * @return void
     */
    public function testCreate()
    {
        $configDecoratorClass = ConfigContextDecorator::class;
        $decoratorMock = $this->createMock(ConfigContextDecorator::class);

        $chainDecorator = new ChainDecorator();
        $chainDecorator->add($decoratorMock);

        $this->config
            ->expects(static::once())
            ->method('get')
            ->with('flagception.decorators')
            ->willReturn([$configDecoratorClass]);

        $this->app
            ->expects(static::once())
            ->method('make')
            ->with($configDecoratorClass)
            ->willReturn($decoratorMock);

        static::assertEquals($chainDecorator, $this->fixture->create());
    }
}