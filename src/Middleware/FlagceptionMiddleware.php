<?php

namespace BestIt\LaravelFlagception\Middleware;

use Closure;
use Flagception\Exception\AlreadyDefinedException;
use Flagception\Manager\FeatureManager;
use Flagception\Model\Context;
use Illuminate\Http\Request;
use Illuminate\Contracts\Config\Repository as Config;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Middleware to guard routes with feature flags.
 *
 * @author André Varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\LaravelFlagception\Middleware
 */
class FlagceptionMiddleware
{
    /**
     * The feature manager.
     *
     * @var FeatureManager $flagceptionService
     */
    private $featureManager;

    /**
     * The laravel config facade.
     *
     * @var Config $config
     */
    private $config;

    /**
     * FlagceptionMiddleware constructor.
     *
     * @param FeatureManager $featureManager
     * @param Config $config
     */
    public function __construct(FeatureManager $featureManager, Config $config)
    {
        $this->featureManager = $featureManager;
        $this->config = $config;
    }

    /**
     * Handle an incoming request.
     * Pass the feature and context.
     * Pass context with the syntax key=value.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string  $feature
     * @param  string[] $contextStrings
     *
     * @throws NotFoundHttpException
     * @throws AlreadyDefinedException
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $feature, ...$contextStrings)
    {
        $context = new Context();
        foreach ($contextStrings as $contextString) {
            $key = explode('=', $contextString)[0];
            $value = explode('=', $contextString)[1];

            $value = ($value === 'true' ? true : ($value === 'false' ? false : $value));
            $context->add($key, $value);
        }

        $configEnabled = $this->config->get('flagception.middleware.enabled') ? true : false;

        if (!$configEnabled || !$this->featureManager->isActive($feature, $context)) {
            throw new NotFoundHttpException();
        }
        return $next($request);
    }
}
