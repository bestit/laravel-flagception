<?php

namespace BestIt\LaravelFlagception\Decorator;

use Flagception\Decorator\ContextDecoratorInterface;
use Flagception\Exception\AlreadyDefinedException;
use Flagception\Model\Context;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * Context decorator that adds global context based on the config.
 *
 * @author André Varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\LaravelFlagception\Decorator
 */
class ConfigContextDecorator implements ContextDecoratorInterface
{
    /**
     * The laravel config facade.
     *
     * @var Config $config
     */
    private $config;

    /**
     * ConfigContextDecorator constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'config';
    }

    /**
     * {@inheritdoc}
     *
     * @throws AlreadyDefinedException
     */
    public function decorate(Context $context)
    {
        foreach ($this->config->get('flagception.context') as $key => $value) {
            $context->add($key, $value);
        }

        return $context;
    }
}
