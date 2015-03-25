<?php

namespace Ekyna\Bundle\CoreBundle\Redirection;

/**
 * Interface ProviderRegistryInterface
 * @package Ekyna\Bundle\CoreBundle\Redirection
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface ProviderRegistryInterface
{
    /**
     * Registers the provider.
     *
     * @param ProviderInterface $provider
     *
     * @throws \InvalidArgumentException
     */
    public function addProvider(ProviderInterface $provider);

    /**
     * Returns the registered providers.
     *
     * @return ProviderInterface[]
     */
    public function getProviders();
}
