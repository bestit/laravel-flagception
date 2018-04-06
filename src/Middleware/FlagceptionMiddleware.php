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
 * Class FlagceptionMiddleware
 *
 * @author andre.varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\LaravelFlagception\Middleware
 */
class FlagceptionMiddleware
{
    /**
     * @var FeatureManager $flagceptionService
     */
    private $featureManager;

    /**
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
     * Pass context with the syntax key=value
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string  $feature
     * @param  string[] $contextStrings
     * @return mixed
     * @throws NotFoundHttpException
     * @throws AlreadyDefinedException
     */
    public function handle(Request $request, Closure $next, string $feature, string ...$contextStrings)
    {
        $context = new Context();
        foreach ($contextStrings as $contextString) {
            $key = explode('=', $contextString)[0];
            $value = explode('=', $contextString)[1];

            $value = ($value === 'true' ? true : ($value === 'false' ? false : $value));
            $context->add($key, $value);
        }

        $configEnabled = $this->config->get('flagception.middleware.enabled') ?? true;
        if (!$configEnabled || !$this->featureManager->isActive($feature, $context)) {
            throw new NotFoundHttpException();
        }
        return $next($request);
    }
}