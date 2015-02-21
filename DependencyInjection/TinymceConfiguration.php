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
        $contentCss = [
            "asset[css/content.css]",
            "asset[bundles/ekynacore/css/tinymce-content.css]",
        ];

        if (0 < strlen($config['ui']['google_font_url'])) {
            $contentCss[] = $config['ui']['google_font_url'];
        }

        // Simple theme: same as default theme
        $simpleTheme = [
            'menubar'            => false,
            'statusbar'          => false,
            'resize'             => false,
            'image_advtab'       => true,
            'table_adv_tab'      => true,
            'paste_as_text'      => true,
            'relative_urls'      => false,
            'entity_encoding'    => 'raw',
            'toolbar_items_size' => 'small',
            'plugins' => [
                "advlist lists paste textcolor autoresize nonbreaking",
            ],
            'toolbar1' => "undo redo | styleselect | bold italic | forecolor backcolor | ".
                          "alignleft aligncenter alignright alignjustify | bullist numlist",
            'content_css' => $contentCss,
        ];

        // Advanced theme with almost all enabled plugins
        $advancedTheme = [
            'menubar'          => false,
            'statusbar'        => true,
            'resize'           => false,
            'image_advtab'     => true,
            'table_adv_tab'    => true,
            'paste_as_text'    => true,
            'relative_urls'    => false,
            'entity_encoding'  => 'raw',
            'image_class_list' => [
                ['title' => 'Responsive', 'value' => 'img-responsive'],
                ['title' => 'Flottant à gauche', 'value' => 'img-float-left'],
                ['title' => 'Flottant à droite', 'value' => 'img-float-right'],
            ],
            'plugins' => [
                "autoresize advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "emoticons paste textcolor template",
            ],
            'toolbar1' => "undo redo removeformat | styleselect | table link image media | code",
            'toolbar2' => "bold italic underline strikethrough forecolor backcolor | alignleft aligncenter " .
                          "alignright alignjustify | bullist numlist outdent indent",
            'external_plugins' => [
                'filemanager' => "/bundles/ekynafilemanager/js/tinymce.plugin.js",
            ],
            'content_css' => $contentCss,
        ];

        $styleFormats = $config['ui']['tinymce_formats'];
        if (is_array($styleFormats) && !empty($styleFormats)) {
            $simpleTheme['style_formats_merge'] = true;
            $simpleTheme['style_formats'] = $styleFormats;
            $advancedTheme['style_formats_merge'] = true;
            $advancedTheme['style_formats'] = $styleFormats;
        }

        return [
            'include_jquery' => false,
            'tinymce_jquery' => false,
            'selector' => '.tinymce',
            'language' => '%locale%',
            //'language_url' => '/bundles/stfalcontinymce/vendor/tinymce-langs/%locale%.js',
            'theme' => [
                'simple'   => $simpleTheme,
                'advanced' => $advancedTheme,
            ],
        ];
    }
} 