<?php

namespace Ekyna\Bundle\CoreBundle\Locale;

/**
 * Class LocaleProviderAwareTrait
 * @package Ekyna\Bundle\CoreBundle\Locale
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
trait LocaleProviderAwareTrait
{
    /**
     * @var LocaleProviderInterface
     */
    protected $localeProvider;

    /**
     * Returns the locale provider.
     *
     * @return LocaleProviderInterface
     */
    public function getLocaleProvider()
    {
        return $this->localeProvider;
    }

    /**
     * Sets the locale provider.
     *
     * @param LocaleProviderInterface $localeProvider
     */
    public function setLocaleProvider(LocaleProviderInterface $localeProvider)
    {
        $this->localeProvider = $localeProvider;
    }
}
