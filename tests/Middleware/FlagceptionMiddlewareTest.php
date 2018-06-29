<?php

namespace BestIt\LaravelFlagception\Tests\Middleware;

use BestIt\LaravelFlagception\Middleware\FlagceptionMiddleware;
use Flagception\Exception\AlreadyDefinedException;
use Flagception\Manager\FeatureManager;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use ReflectionException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class FlagceptionMiddlewareTest
 *
 * @author André Varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\LaravelFlagception\Tests\Middleware
 */
class FlagceptionMiddlewareTest extends TestCase
{
    /**
     * The flagception middleware.
     *
     * @var FlagceptionMiddleware $fixture
     */
    private $fixture;

    /**
     * The test feature name.
     *
     * @var string $feature
     */
    private $feature = 'feature_123';

    /**
     * The config facade.
     *
     * @var Config|PHPUnit_Framework_MockObject_MockObject $config
     */
    private $config;

    /**
     * The feature manager.
     *
     * @var FeatureManager|PHPUnit_Framework_MockObject_MockObject $featureManager
     */
    private $featureManager;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new FlagceptionMiddleware(
            $this->featureManager = $this->createMock(FeatureManager::class),
            $this->config = $this->createMock(Config::class)
        );
    }

    /**
     * Test the handle method with an active feature.
     *
     * @return void
     *
     * @throws AlreadyDefinedException
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

        $result = $this->fixture->handle(
            $request,
            function () {
                return true;
            },
            $this->feature
        );
        $this->assertTrue($result);
    }

    /**
     * Test the handle method disabled in config.
     *
     * @return void
     *
     * @throws AlreadyDefinedException
     */
    public function testHandleWithConfigDisabled()
    {
        static::expectException(NotFoundHttpException::class);

        $this->config
            ->expects(static::once())
            ->method('get')
            ->with('flagception.middleware.enabled')
            ->willReturn(false);

        $request = $this->createMock(Request::class);
        $this->fixture->handle(
            $request,
            function () {
                return true;
            },
            $this->feature
        );
    }

    /**
     * Test the handle method with inactive feature.
     *
     * @return void
     *
     * @throws AlreadyDefinedException
     */
    public function testHandleWithInactiveFeature()
    {
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
        $this->fixture->handle(
            $request,
            function () {
                return true;
            },
            $this->feature
        );
    }


}