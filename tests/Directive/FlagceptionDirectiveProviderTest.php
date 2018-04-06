<?php

namespace BestIt\LaravelFlagception\Tests\Directive;

use BestIt\LaravelFlagception\Directive\FlagceptionDirectiveProvider;
use Flagception\Manager\FeatureManager;
use Illuminate\View\Compilers\BladeCompiler;
use PHPUnit\Framework\TestCase;

/**
 * Class FlagceptionDirectiveProviderTest
 *
 * @author andre.varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\LaravelFlagception\Tests\Directive
 */
class FlagceptionDirectiveProviderTest extends TestCase
{
    /**
     * @var FlagceptionDirectiveProvider $fixture
     */
    private $fixture;

    /**
     * @var FeatureManager $featureManager;
     */
    private $featureManager;

    /**
     * @var BladeCompiler $bladeCompiler
     */
    private $bladeCompiler;

    /**
     * @inheritdoc
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
     */
    public function testRegisterDirective()
    {
        $this->bladeCompiler
            ->expects(static::once())
            ->method('if');
        $this->fixture->registerDirective();
    }
}