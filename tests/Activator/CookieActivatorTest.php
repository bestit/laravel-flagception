<?php

namespace BestIt\LaravelFlagception\Tests\Activator;

use BestIt\LaravelFlagception\Activator\CookieActivator;
use Flagception\Model\Context;
use Illuminate\Contracts\Config\Repository as Config;
use PHPUnit\Framework\TestCase;

/**
 * Class CookieActivatorTest
 *
 * @author andre.varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\LaravelFlagception\Tests\Activator
 */
class CookieActivatorTest extends TestCase
{
    /**
     * @var CookieActivator $fixture
     */
    private $fixture;

    /**
     * @var Config $config
     */
    private $config;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->fixture = new CookieActivator(
            $this->config = $this->createMock(Config::class)
        );
    }

    /**
     * Test the get name method
     */
    public function testGetName()
    {
        static::assertEquals('cookie', $this->fixture->getName());
    }

    /**
     * Test for the isActive method
     *
     * @dataProvider getActivatorSettings
     */
    public function testIsActive(
        string $feature,
        string $cookieString,
        bool $cookieEnabled,
        bool $expectedResult
    ) {
        $this->config
            ->expects(static::exactly(3))
            ->method('get')
            ->withConsecutive(
                ['flagception.features.' . $feature . '.cookie'],
                ['flagception.cookie.name'],
                ['flagception.cookie.delimiter']
            )
            ->willReturnOnConsecutiveCalls(
                $cookieEnabled,
                CookieActivator::DEFAULT_COOKIE_NAME,
                CookieActivator::DEFAULT_COOKIE_DELIMITER
            );

        $_COOKIE[CookieActivator::DEFAULT_COOKIE_NAME] = $cookieString;

        static::assertEquals($this->fixture->isActive($feature, new Context()), $expectedResult);
    }

    /**
     * Possible CookieActivator settings
     *
     * @return array
     */
    public function getActivatorSettings(): array
    {
        return [
            ['feature', 'feature', true, true],
            ['feature', 'feature', false, false],
            ['feature', 'feature,feature_2', true, true],
            ['feature', 'feature_2,feature_3', true, false],
        ];
    }
}