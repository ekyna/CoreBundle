<?php

namespace Ekyna\Bundle\CoreBundle\DependencyInjection;

/**
 * Class TinymceConfiguration
 * @package Ekyna\Bundle\CoreBundle\DependencyInjection
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class TinymceConfiguration
{
    /**
     * Builds the tinymce configuration.
     *
     * @param array $config
     * @return array
     */
    public function build(array $config)
    {
        $contentCss = array(
            "asset[css/content.css]",
            "asset[bundles/ekynacore/css/tinymce-content.css]",
        );

        if (0 < strlen($config['ui']['google_font_url'])) {
            $contentCss[] = $config['ui']['google_font_url'];
        }

        // Simple theme: same as default theme
        $simpleTheme = array(
            'menubar'       => false,
            'statusbar'     => false,
            'resize'        => false,
            'image_advtab'  => true,
            'relative_urls' => false,
            'entity_encoding' => 'raw',
            'toolbar_items_size' => 'small',
            'plugins' => array(
                "advlist autolink lists link image anchor paste textcolor",
                "autoresize nonbreaking table contextmenu directionality",
            ),
            'toolbar1' =>   "undo redo | styleselect | bold italic | forecolor backcolor | ".
                "alignleft aligncenter alignright alignjustify | bullist numlist | link image",
            'content_css' => $contentCss,
        );

        // Advanced theme with almost all enabled plugins
        $advancedTheme = array(
            'menubar'       => false,
            'statusbar'     => true,
            'resize'        => false,
            'image_advtab'  => true,
            'relative_urls' => false,
            'entity_encoding' => 'raw',
            'plugins' => array(
                "autoresize advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "emoticons paste textcolor template",
            ),
            'toolbar1' =>   "undo redo | styleselect | link image media | code",
            'toolbar2' =>   "bold italic forecolor backcolor | alignleft aligncenter " .
                "alignright alignjustify | bullist numlist outdent indent",
            'external_plugins' => array(
                'filemanager' => "/bundles/ekynafilemanager/js/tinymce.plugin.js",
            ),
            'content_css' => $contentCss,
        );

        $styleFormats = $config['ui']['tinymce_formats'];
        if (is_array($styleFormats) && !empty($styleFormats)) {
            $simpleTheme['style_formats_merge'] = true;
            $simpleTheme['style_formats'] = $styleFormats;
            $advancedTheme['style_formats_merge'] = true;
            $advancedTheme['style_formats'] = $styleFormats;
        }

        return array(
            'include_jquery' => false,
            'tinymce_jquery' => false,
            'selector' => '.tinymce',
            'language' => '%locale%',
            //'language_url' => '/bundles/stfalcontinymce/vendor/tinymce-langs/%locale%.js',
            'theme' => array(
                'simple' => $simpleTheme,
                'advanced' => $advancedTheme,
            ),
        );
    }
} 