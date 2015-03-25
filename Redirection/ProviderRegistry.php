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
     * @var bool
     */
    private $initialized = false;

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
        if ($this->initialized) {
            throw new \RuntimeException('Redirection registry as been initialized and can\'t register more providers.');
        }
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
        if (!$this->initialized) {
            usort($this->providers, function (ProviderInterface $a, ProviderInterface $b) {
                if ($a->getPriority() == $b->getPriority()) {
                    return 0;
                }
                return $a->getPriority() > $b->getPriority() ? 1 : -1;
            });
            $this->initialized = true;
        }
        return $this->providers;
    }
}
