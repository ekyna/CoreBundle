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

        // Fix path in output dir
        if ('/' !== substr($config['output_dir'], -1) && strlen($config['output_dir']) > 0) {
            $config['output_dir'] .= '/';
        }

        $output['content_css'] = $this->buildContentCss($config);
        $output['core_css'] = $this->buildCoreCss($config);
        $output['core_js'] = $this->buildCoreJs($config);

        return $output;
    }

    /**
     * @param array $config
     * @return array
     */
    protected function buildContentCss(array $config)
    {
        $inputs = $config['content_css'];

        return array(
            'inputs'  => $inputs,
            'filters' => array('cssrewrite', 'yui_css'),
            'output'  => $config['output_dir'].'css/content.css',
            'debug'   => false,
        );
    }

    /**
     * @param array $config
     * @return array
     */
    protected function buildCoreCss(array $config)
    {
        $inputs = array(
            '@EkynaCoreBundle/Resources/asset/css/lib/bootstrap.datetimepicker.css',
            '@EkynaCoreBundle/Resources/asset/css/lib/bootstrap.colorpickersliders.css',
            '@EkynaCoreBundle/Resources/asset/css/lib/select2.css',
            '@EkynaCoreBundle/Resources/asset/css/form.css',
            '@EkynaCoreBundle/Resources/asset/css/modal-gallery.css',
        );

        return array(
            'inputs'  => $inputs,
            'filters' => array('yui_css'), // 'cssrewrite'
            'output'  => $config['output_dir'].'css/core.css',
            'debug'   => false,
        );
    }

    /**
     * @param array $config
     * @return array
     */
    protected function buildCoreJs(array $config)
    {
        $inputs = array(
    	    'bundles/fosjsrouting/js/router.js',
            '%kernel.root_dir%/../vendor/malsup/form/jquery.form.js',
            '@EkynaCoreBundle/Resources/asset/js/lib/tinycolor.js',
            '@EkynaCoreBundle/Resources/asset/js/lib/bootstrap.datetimepicker.js',
            '@EkynaCoreBundle/Resources/asset/js/lib/bootstrap.colorpickersliders.js',
            '@EkynaCoreBundle/Resources/asset/js/lib/jquery.autosize.js',
            '@EkynaCoreBundle/Resources/asset/js/lib/select2.js',
            '@EkynaCoreBundle/Resources/asset/js/lib/load-image.js',
            '@EkynaCoreBundle/Resources/asset/js/modal-gallery.js',
            '@EkynaCoreBundle/Resources/asset/js/string.prototypes.js',
            '@EkynaCoreBundle/Resources/asset/js/forms.js',
        );

        return array(
            'inputs'  => $inputs,
            'filters' => array('yui_js'),
            'output'  => $config['output_dir'].'js/core.js',
            'debug'   => false,
        );
    }
}
