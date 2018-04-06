<?php

namespace BestIt\LaravelFlagception\Directive;

use Flagception;
use Flagception\Model\Context;
use Illuminate\View\Compilers\BladeCompiler;
use Flagception\Manager\FeatureManager;

/**
 * Class FlagceptionDirectiveProvider
 *
 * @author andre.varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\LaravelFlagception\Directive
 */
class FlagceptionDirectiveProvider
{
    /**
     * @var FeatureManager $featureManager
     */
    private $featureManager;

    /**
     * @var BladeCompiler $bladeCompiler;
     */
    private $bladeCompiler;

    /**
     * FlagceptionDirectiveProvider constructor.
     *
     * @param FeatureManager $featureManager
     * @param BladeCompiler $bladeCompiler
     */
    public function __construct(FeatureManager $featureManager, BladeCompiler $bladeCompiler)
    {
        $this->featureManager = $featureManager;
        $this->bladeCompiler = $bladeCompiler;
    }

    /**
     * Register blade directive
     */
    public function registerDirective() {
        $this->bladeCompiler->if('feature', function ($feature, $contextArray = []) {

            $context = new Context();
            foreach ($contextArray as $key => $value) {
                $context->add($key, $value);
            }

            return $this->featureManager->isActive($feature, $context);
        });
    }
}