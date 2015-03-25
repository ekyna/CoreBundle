<?php

namespace Ekyna\Bundle\CoreBundle\Redirection;

/**
 * Class ProviderRegistry
 * @package Ekyna\Bundle\CoreBundle\Redirection
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ProviderRegistry implements ProviderRegistryInterface
{
    /**
     * @var array|ProviderInterface[]
     */
    private $providers;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->providers = [];
    }

    /**
     * {@inheritdoc}
     */
    public function addProvider(ProviderInterface $provider)
    {
        if (array_key_exists($provider->getName(), $this->providers)) {
            throw new \InvalidArgumentException(sprintf('Provider "%s" is already registered.', $provider->getName()));
        }
        $this->providers[$provider->getName()] = $provider;
    }

    /**
     * {@inheritdoc}
     */
    public function getProviders()
    {
        return $this->providers;
    }
}
