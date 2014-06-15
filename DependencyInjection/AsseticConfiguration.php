<?php

namespace Ekyna\Bundle\CoreBundle\DependencyInjection;

/**
 * AsseticConfiguration
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AsseticConfiguration
{
    /**
     * Builds the assetic configuration.
     *
     * @param array $config
     * @param string $root_dir
     *
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
     * @param string $root_dir
     *
     * @return array
     */
    protected function buildContentCss(array $config)
    {
        $inputs = array(
            '@bootstrap_css',
	        '%kernel.root_dir%/Resources/public/css/content.css'
        );

        return array(
            'inputs'  => $inputs,
            'filters' => array('cssrewrite', 'yui_css'),
            'output'  => $config['output_dir'].'css/content.css',
            'debug'   => false,
        );
    }

    /**
     * @param array $config
     * @param string $root_dir
     *
     * @return array
     */
    protected function buildCoreCss(array $config)
    {
        $inputs = array(
            'bundles/ekynacore/css/lib/bootstrap.datetimepicker.css',
            'bundles/ekynacore/css/lib/select2.css',
            'bundles/ekynacore/css/form.css',
            'bundles/ekynacore/css/modal-gallery.css',
        );

        return array(
            'inputs'  => $inputs,
            'filters' => array('cssrewrite', 'yui_css'),
            'output'  => $config['output_dir'].'css/core.css',
            'debug'   => false,
        );
    }

    /**
     * @param array $config
     * @param string $root_dir
     *
     * @return array
     */
    protected function buildCoreJs(array $config)
    {
        $inputs = array(
    	    'bundles/fosjsrouting/js/router.js',
            '%kernel.root_dir%/../vendor/malsup/form/jquery.form.js',
            'bundles/ekynacore/js/lib/jquery.autosize.min.js',
            'bundles/ekynacore/js/lib/bootstrap.datetimepicker.js',
            'bundles/ekynacore/js/lib/select2.min.js',
            'bundles/ekynacore/js/lib/load-image.js',
            'bundles/ekynacore/js/modal-gallery.js',
            'bundles/ekynacore/js/string.prototypes.js',
            'bundles/ekynacore/js/forms.js',
        );

        return array(
            'inputs'  => $inputs,
            'filters' => array('closure'),
            'output'  => $config['output_dir'].'js/core.js',
            'debug'   => false,
        );
    }
}
