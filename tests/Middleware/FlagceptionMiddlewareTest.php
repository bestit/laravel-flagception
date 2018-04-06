<?php

namespace BestIt\LaravelFlagception\Tests\Middleware;

use BestIt\LaravelFlagception\Middleware\FlagceptionMiddleware;
use Flagception\Manager\FeatureManager;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class FlagceptionMiddlewareTest
 *
 * @author andre.varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\LaravelFlagception\Tests\Middleware
 */
class FlagceptionMiddlewareTest extends TestCase
{
    /**
     * @var FlagceptionMiddleware $fixture
     */
    private $fixture;

    /**
     * @var string $feature
     */
    private $feature = 'feature_123';

    /**
     * @var Config $config
     */
    private $config;

    /**
     * @var FeatureManager $featureManager
     */
    private $featureManager;

    /**
     * @throws \ReflectionException
     */
    public function setUp()
    {
        $this->fixture = new FlagceptionMiddleware(
            $this->featureManager = $this->createMock(FeatureManager::class),
            $this->config = $this->createMock(Config::class)
        );
    }

    /**
     * Test the handle method with an active feature;
     *
     * @throws \ReflectionException
     * @return void
     */
    public function testHandleWithActiveFeature()
    {
        $this->config
            ->expects(static::once())
            ->method('get')
            ->with('flagception.middleware.enabled')
            ->willReturn(true);

        $this->featureManager
            ->expects(static::once())
            ->method('isActive')
            ->with($this->feature)
            ->willReturn(true);

        $request = $this->createMock(Request::class);

        $result = $this->fixture->handle($request, function () { return true;}, $this->feature);
        $this->assertTrue($result);
    }

    /**
     * Test the handle method disabled in config
     *
     * @throws \ReflectionException
     * @return void
     */
    public function testHandleWithConfigDisabled() {
        static::expectException(NotFoundHttpException::class);

        $this->config
            ->expects(static::once())
            ->method('get')
            ->with('flagception.middleware.enabled')
            ->willReturn(false);

        $request = $this->createMock(Request::class);
        $this->fixture->handle($request, function () { return true;}, $this->feature);
    }

    /**
     * Test the handle method with inactive feature
     *
     * @throws \ReflectionException
     * @return void
     */
    public function testHandleWithInactiveFeature() {
        static::expectException(NotFoundHttpException::class);

        $this->config
            ->expects(static::once())
            ->method('get')
            ->with('flagception.middleware.enabled')
            ->willReturn(true);

        $this->featureManager
            ->expects(static::once())
            ->method('isActive')
            ->with($this->feature)
            ->willReturn(false);

        $request = $this->createMock(Request::class);
        $this->fixture->handle($request, function () { return true;}, $this->feature);
    }


}