<?php

namespace Ekyna\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension as BaseExtension;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Extension
 * @package Ekyna\Bundle\CoreBundle\DependencyInjection
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class Extension extends BaseExtension implements PrependExtensionInterface
{
    protected $configDirectory = '/../Resources/config';


    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        if (is_dir($dir = $this->getConfigurationDirectory().'/prepend')) {
            $bundles = $container->getParameter('kernel.bundles');
            $finder = new Finder();

            /** @var \Symfony\Component\Finder\SplFileInfo $file */
            foreach ($finder->in($this->getConfigurationDirectory() . '/prepend')->files()->name('*.yml') as $file) {
                $bundle = $file->getBasename('.yml');
                if (array_key_exists($bundle, $bundles)) {
                    $configs = Yaml::parse($file->getRealPath());
                    foreach ($configs as $key => $config) {
                        $container->prependExtensionConfig($key, $config);
                    }
                }
            }
        }
    }

    /**
     * Returns the configuration directory.
     *
     * @return string
     * @throws \Exception
     */
    protected function getConfigurationDirectory()
    {
        $reflector = new \ReflectionClass($this);
        $fileName = $reflector->getFileName();

        if (!is_dir($directory = realpath(dirname($fileName) . $this->configDirectory))) {
            throw new \Exception(sprintf('The configuration directory "%s" does not exists.', $directory));
        }

        return $directory;
    }
}
