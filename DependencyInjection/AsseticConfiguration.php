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
        if ($config['content_css']['enabled']) {
            $output['content_css'] = $this->buildContentCss($config);
        }

        $output['twig_js'] = $this->buildTwigJs($config);
        $output['form_css'] = $this->buildFormCss($config);

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

        $inputs[] = 'assets/bootstrap-dialog/dist/css/bootstrap-dialog.min.css';

        return array(
            'inputs'  => $inputs,
            'filters' => array('cssrewrite', 'less', 'yui_css'),
            'output'  => $dir . 'css/bootstrap.css',
            'debug'   => false,
        );
    }

    /**
     * Builds the jquery asset collection configuration.
     *
     * @param array $config
     * @return array
     */
    protected function buildTwigJs(array $config)
    {
        $dir = $config['output_dir'];
        $inputs = array('%kernel.root_dir%/../vendor/jms/twig-js/twig.js');

        return array(
            'inputs'  => $inputs,
//            'filters' => array('yui_js'),
            'output'  => $dir . 'js/twig.js',
            'debug'   => false,
        );
    }

    /**
     * Builds the core_css asset collection configuration.
     *
     * @param array $config
     * @return array
     */
    protected function buildFormCss(array $config)
    {
        $inputs = array_merge(array(
            '%kernel.root_dir%/../web/assets/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css',
            '@EkynaCoreBundle/Resources/asset/css/lib/bootstrap.colorpickersliders.css',
            '@EkynaCoreBundle/Resources/asset/css/lib/select2.css',
            '@EkynaCoreBundle/Resources/asset/css/lib/jquery.qtip.css',
            '@EkynaCoreBundle/Resources/asset/css/form.css',
            //'@EkynaCoreBundle/Resources/asset/css/modal-gallery.css',
        ), $config['form_css']['inputs']);

        return array(
            'inputs'  => $inputs,
            'filters' => array('yui_css'), // 'cssrewrite'
            'output'  => $config['output_dir'] . 'css/form.css',
            'debug'   => false,
        );
    }
}
