<?php

namespace BestIt\LaravelFlagception\Decorator;

use Flagception\Decorator\ContextDecoratorInterface;
use Flagception\Model\Context;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * Class DefaultContextDecorator
 *
 * @author andre.varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\LaravelFlagception\Decorator
 */
class ConfigContextDecorator implements ContextDecoratorInterface
{
    /**
     * @var Config $config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'config';
    }

    /**
     * @inheritDoc
     */
    public function decorate(Context $context)
    {
        foreach ($this->config->get('flagception.context') as $key => $value) {
            $context->add($key, $value);
        }
        return $context;
    }
}