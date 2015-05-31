<?php

namespace Ekyna\Bundle\CoreBundle\DependencyInjection;

/**
 * Class AsseticConfiguration
 * @package Ekyna\Bundle\CoreBundle\DependencyInjection
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AsseticConfiguration
{
    /**
     * Builds the assetic configuration.
     *
     * @param array $config
     * @return array
     */
    public function build(array $config)
    {
        $output = array();

        // Fix output dir trailing slash
        if ('/' !== substr($config['output_dir'], -1) && strlen($config['output_dir']) > 0) {
            $config['output_dir'] .= '/';
        }

        if ($config['bootstrap_css']['enabled']) {
            $output['bootstrap_css'] = $this->buildBootstrapCss($config);
        }
        if ($config['bootstrap_js']['enabled']) {
            $output['bootstrap_js'] = $this->buildBootstrapJs($config);
        }
        if ($config['jquery']['enabled']) {
            $output['jquery'] = $this->buildJQuery($config);
        }
        if ($config['content_css']['enabled']) {
            $output['content_css'] = $this->buildContentCss($config);
        }
        $output['core_css'] = $this->buildCoreCss($config);
        $output['core_js'] = $this->buildCoreJs($config);

        return $output;
    }

    /**
     * Builds the content_css asset collection configuration.
     *
     * @param array $config
     * @return array
     */
    protected function buildContentCss(array $config)
    {
        $dir = $config['output_dir'];
        $inputs = $config['content_css']['inputs'];

        return array(
            'inputs'  => $inputs,
            'filters' => array('cssrewrite', 'yui_css'),
            'output'  => $dir . 'css/content.css',
            'debug'   => false,
        );
    }

    /**
     * Builds the bootstrap_css asset collection configuration.
     *
     * @param array $config
     * @return array
     */
    protected function buildBootstrapCss(array $config)
    {
        $dir = $config['output_dir'];
        $inputs = $config['bootstrap_css']['inputs'];

        return array(
            'inputs'  => $inputs,
            'filters' => array('cssrewrite', 'less', 'yui_css'),
            'output'  => $dir . 'css/bootstrap.css',
            'debug'   => false,
        );
    }

    /**
     * Builds the bootstrap_js asset collection configuration.
     *
     * @param array $config
     * @return array
     */
    protected function buildBootstrapJs(array $config)
    {
        $dir = $config['output_dir'];
        $plugins = $config['bootstrap_js']['plugins'];

        $inputs = array();

        $bsAssetDir = '%kernel.root_dir%/../vendor/twbs/bootstrap/js/';
        $pluginsKeys = array(
            'transition', 'alert', 'button', 'carousel', 'collapse', 'dropdown',
            'modal', 'tooltip', 'popover', 'scrollspy', 'tab', 'affix',
        );
        foreach($pluginsKeys as $pluginsKey) {
            if (true === $plugins[$pluginsKey]) {
                $inputs[] = $bsAssetDir . $pluginsKey . '.js';
            }
        }

        /*if (true === $plugins['collection']) {
            $inputs[] =
                '%kernel.root_dir%/../vendor/braincrafted/bootstrap-bundle/Braincrafted/Bundle/'.
                'BootstrapBundle/Resources/js/bc-bootstrap-collection.js';
        }*/

        return array(
            'inputs'  => $inputs,
            'filters' => array('yui_js'),
            'output'  => $dir . 'js/bootstrap.js',
            'debug'   => false,
        );
    }

    /**
     * Builds the jquery asset collection configuration.
     *
     * @param array $config
     * @return array
     */
    protected function buildJQuery(array $config)
    {
        $dir = $config['output_dir'];
        $inputs = $config['jquery']['inputs'];

        return array(
            'inputs'  => $inputs,
            'filters' => array('yui_js'),
            'output'  => $dir . 'js/jquery.js',
            'debug'   => false,
        );
    }

    /**
     * Builds the core_css asset collection configuration.
     *
     * @param array $config
     * @return array
     */
    protected function buildCoreCss(array $config)
    {
        $inputs = array(
            '%kernel.root_dir%/../vendor/eonasdan/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css',
            '@EkynaCoreBundle/Resources/asset/css/lib/bootstrap.colorpickersliders.css',
            '@EkynaCoreBundle/Resources/asset/css/lib/select2.css',
            '@EkynaCoreBundle/Resources/asset/css/lib/jquery.qtip.css',
            '@EkynaCoreBundle/Resources/asset/css/form.css',
            '@EkynaCoreBundle/Resources/asset/css/modal-gallery.css',
        );

        return array(
            'inputs'  => $inputs,
            'filters' => array('yui_css'), // 'cssrewrite'
            'output'  => $config['output_dir'] . 'css/core.css',
            'debug'   => false,
        );
    }

    /**
     * Builds the core_js asset collection configuration.
     *
     * @param array $config
     * @return array
     */
    protected function buildCoreJs(array $config)
    {
        $inputs = array(
    	    'bundles/fosjsrouting/js/router.js',
            '%kernel.root_dir%/../vendor/malsup/form/jquery.form.js',
            '%kernel.root_dir%/../vendor/moment/moment/min/moment-with-locales.min.js',
            '%kernel.root_dir%/../vendor/eonasdan/bootstrap-datetimepicker/src/js/bootstrap-datetimepicker.js',
            '@EkynaCoreBundle/Resources/asset/js/lib/tinycolor.js',
            '@EkynaCoreBundle/Resources/asset/js/lib/bootstrap.colorpickersliders.js',
            '@EkynaCoreBundle/Resources/asset/js/lib/bootstrap.hover-dropdown.js',
            '@EkynaCoreBundle/Resources/asset/js/lib/jquery.autosize.js',
            '@EkynaCoreBundle/Resources/asset/js/lib/select2.js',
            '@EkynaCoreBundle/Resources/asset/js/lib/jquery.qtip.js',
            '@EkynaCoreBundle/Resources/asset/js/lib/load-image.js',
            '@EkynaCoreBundle/Resources/asset/js/lib/fileupload/jquery.ui.widget.js',
            '@EkynaCoreBundle/Resources/asset/js/lib/fileupload/jquery.fileupload.js',
            //'@EkynaCoreBundle/Resources/asset/js/lib/fileupload/jquery.fileupload-validate.js',
            '@EkynaCoreBundle/Resources/asset/js/modal-gallery.js',
            '@EkynaCoreBundle/Resources/asset/js/string.prototypes.js',
            '@EkynaCoreBundle/Resources/asset/js/form.collection.js',
            '@EkynaCoreBundle/Resources/asset/js/forms.js',
            '@EkynaCoreBundle/Resources/asset/js/router.js',
        );

        return array(
            'inputs'  => $inputs,
            'filters' => array('yui_js'),
            'output'  => $config['output_dir'] . 'js/core.js',
            'debug'   => false,
        );
    }
}
