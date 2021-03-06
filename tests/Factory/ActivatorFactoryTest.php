<?php

namespace BestIt\LaravelFlagception\Tests\Factory;

use BestIt\LaravelFlagception\Activator\CookieActivator;
use BestIt\LaravelFlagception\Factory\ActivatorCompositeFactory;
use Flagception\Constraint\Provider\DateProvider;
use Flagception\Model\Context;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Foundation\Application as App;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class ActivatorFactoryTest
 *
 * @author André Varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\LaravelFlagception\Tests\Factory
 */
class ActivatorFactoryTest extends TestCase
{
    /**
     * The activator composite factory.
     *
     * @var ActivatorCompositeFactory $fixture
     */
    private $fixture;

    /**
     * The config facade.
     *
     * @var Config|PHPUnit_Framework_MockObject_MockObject $config
     */
    private $config;

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
        $this->fixture = new ActivatorCompositeFactory(
            $this->config = $this->createMock(Config::class),
            $this->app = $this->createMock(App::class)
        );
    }

    /**
     * Test the create method.
     *
     * @return void
     */
    public function testCreate()
    {
        $cookieActivatorClass = CookieActivator::class;
        $dateProviderClass = DateProvider::class;
        $feature = 'feature_1';

        $featureConfig = [
            $feature => [
                'active' => true,
                'constraint' => 'user_id == 1'
            ]
        ];

        $this->config
            ->expects(static::exactly(4))
            ->method('get')
            ->withConsecutive(
                ['flagception.features'],
                ['flagception.features'],
                ['flagception.expressions'],
                ['flagception.activators']
            )
            ->willReturnOnConsecutiveCalls(
                $featureConfig,
                $featureConfig,
                [$dateProviderClass],
                [$cookieActivatorClass]
            );

        $this->app
            ->expects(static::exactly(2))
            ->method('make')
            ->withConsecutive(
                [$dateProviderClass],
                [$cookieActivatorClass]
            )
            ->willReturnOnConsecutiveCalls(
                new $dateProviderClass(),
                new $cookieActivatorClass($this->config)
            );

        $chainActivator = $this->fixture->create();

        static::assertTrue($chainActivator->isActive($feature, new Context()));
    }
}