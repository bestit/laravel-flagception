<?php

namespace BestIt\LaravelFlagception\Tests\Directive;

use BestIt\LaravelFlagception\Directive\FlagceptionDirectiveProvider;
use Flagception\Manager\FeatureManager;
use Illuminate\View\Compilers\BladeCompiler;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class FlagceptionDirectiveProviderTest
 *
 * @author André Varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\LaravelFlagception\Tests\Directive
 */
class FlagceptionDirectiveProviderTest extends TestCase
{
    /**
     * The Flagception directive provider.
     *
     * @var FlagceptionDirectiveProvider $fixture
     */
    private $fixture;

    /**
     * The feature manager.
     *
     * @var FeatureManager|PHPUnit_Framework_MockObject_MockObject $featureManager
     */
    private $featureManager;

    /**
     * The blade compiler.
     *
     * @var BladeCompiler|PHPUnit_Framework_MockObject_MockObject $bladeCompiler
     */
    private $bladeCompiler;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new FlagceptionDirectiveProvider(
            $this->featureManager = $this->createMock(FeatureManager::class),
            $this->bladeCompiler = $this->createMock(BladeCompiler::class)
        );
    }

    /**
     * Test register directive
     * TODO: test the closure
     *
     * @return void
     */
    public function testRegisterDirective()
    {
        $this->bladeCompiler
            ->expects(static::once())
            ->method('if');
        $this->fixture->registerDirective();
    }
}