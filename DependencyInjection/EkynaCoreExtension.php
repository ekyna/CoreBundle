<?php

namespace Ekyna\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class EkynaCoreExtension
 * @package Ekyna\Bundle\CoreBundle\DependencyInjection
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class EkynaCoreExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $routers = (array) $config['chain_router']['routers_by_id'];
        if (!empty($routers)) {
            $container->setParameter('ekyna_core.chain_router.routers', $routers);
        }

        if (!in_array('bundles/ekynacore/css/core.css', $config['ui']['stylesheets']['forms'])) {
            $config['ui']['stylesheets']['forms'][] = 'bundles/ekynacore/css/core.css';
        }

        $container->setParameter('ekyna_core.config.ui', $config['ui']);
        $container->setParameter('ekyna_core.config.cache', $config['cache']);

        /* Inheritance mapping = [
         *     resource_id => [
         *         'class' => Class ,
         *         'repository' => Repository class ,
         *     ]
         * ] */
        if (!$container->hasParameter('ekyna_core.entities')) {
            $container->setParameter('ekyna_core.entities', []);
        }

        /* Target entities resolution
         * [ Interface => Class or class parameter ]
         */
        if (!$container->hasParameter('ekyna_core.interfaces')) {
            $container->setParameter('ekyna_core.interfaces', []);
        }

        $bundles = $container->getParameter('kernel.bundles');
        $tinymceCfgBuilder = new TinymceConfigBuilder();
        $tinymceConfig = $tinymceCfgBuilder->build($config, $bundles) ;

        $container->setParameter('ekyna_core.config.tinymce', $tinymceConfig);
        $container->setParameter('ekyna_core.config.tinymce_themes', array_keys($tinymceConfig['theme']));
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        parent::prepend($container);

        $bundles = $container->getParameter('kernel.bundles');
        $configs = $container->getExtensionConfig($this->getAlias());
        $config = $this->processConfiguration(new Configuration(), $configs);

        if ($config['cache']['enable'] && array_key_exists('FOSHttpCacheBundle', $bundles)) {
            $this->configureFOSHttpCacheBundle($container);
        }
    }

    /**
     * Configures the FOSHttpCacheBundle.
     *
     * @param ContainerBuilder $container
     */
    protected function configureFOSHttpCacheBundle(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('fos_http_cache', [
            'proxy_client' => [
                'default' =>  'varnish',
                'varnish' => [
                    'servers' =>  "%reverse_proxy.host%:%reverse_proxy.port%",
                    'base_url' => "%hostname%:%reverse_proxy.port%",
                ],
            ],
        ]);
    }
}
