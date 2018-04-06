<?php

namespace BestIt\LaravelFlagception\Activator;

use Illuminate\Contracts\Config\Repository as Config;
use Flagception\Activator\FeatureActivatorInterface;
use Flagception\Model\Context;
use Flagception;

/**
 * Class CookieActivator
 *
 * @author andre.varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\LaravelFlagception\Activator
 */
class CookieActivator implements FeatureActivatorInterface
{
    /**
     * default cookie name
     *
     * @var string DEFAULT_COOKIE_NAME
     */
    const DEFAULT_COOKIE_NAME = 'flagception';

    /**
     * default cookie delimiter
     *
     * @var string DEFAULT_COOKIE_DELIMITER
     */
    const DEFAULT_COOKIE_DELIMITER = ',';

    /**
     * @var Config $config
     */
    private $config;

    /**
     * CookieActivator constructor.
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
        return 'cookie';
    }

    /**
     * {@inheritdoc}
     */
    public function isActive($name, Context $context)
    {
        $configEnabled = $this->config->get('flagception.features.' . $name . '.cookie') ?? true;
        $cookieName = $this->config->get('flagception.cookie.name') ?? self::DEFAULT_COOKIE_NAME;
        $delimiter = $this->config->get('flagception.cookie.delimiter') ?? self::DEFAULT_COOKIE_DELIMITER;

        return $configEnabled
            && isset($_COOKIE[$cookieName])
            && in_array($name, explode($delimiter, $_COOKIE[$cookieName]));
    }


}