<?php

namespace BestIt\LaravelFlagception\Tests\Decorator;

use BestIt\LaravelFlagception\Decorator\ConfigContextDecorator;
use Flagception\Exception\AlreadyDefinedException;
use Flagception\Model\Context;
use Illuminate\Contracts\Config\Repository as Config;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class ConfigContextDecoratorClass
 *
 * @author André Varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\LaravelFlagception\Tests\Decorator
 */
class ConfigContextDecoratorClass extends TestCase
{
    /**
     * The config context decorator.
     *
     * @var ConfigContextDecorator $fixture
     */
    private $fixture;

    /**
     * The config facade.
     *
     * @var Config|PHPUnit_Framework_MockObject_MockObject $config
     */
    private $config;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new ConfigContextDecorator(
            $this->config = $this->createMock(Config::class)
        );
    }

    /**
     * Test the get name method.
     *
     * @return void
     */
    public function testGetName()
    {
        static::assertEquals('config', $this->fixture->getName());
    }

    /**
     * Test the decorate method.
     *
     * @return void
     *
     * @throws AlreadyDefinedException
     */
    public function testDecorate()
    {
        $contextArray = [
            'key' => 'value'
        ];
        $context = new Context();
        foreach ($contextArray as $key => $value) {
            $context->add($key, $value);
        }

        $this->config
            ->expects(static::once())
            ->method('get')
            ->with('flagception.context')
            ->willReturn($contextArray);

        static::assertEquals($context, $this->fixture->decorate(new Context()));
    }
}