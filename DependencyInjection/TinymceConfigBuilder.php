<?php

namespace Ekyna\Bundle\CoreBundle\DependencyInjection;

/**
 * Class TinymceConfigBuilder
 * @package Ekyna\Bundle\CoreBundle\DependencyInjection
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class TinymceConfigBuilder
{
    /**
     * Builds the tinymce configuration.
     *
     * @param array $config
     * @param array $bundles
     * @return array
     */
    public function build(array $config, array $bundles)
    {
        $contentCss = [
            'asset[' . $config['ui']['stylesheets']['content'] . ']',
            'asset[bundles/ekynacore/css/tinymce-content.css]',
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
            'table_advtab'       => true,
            'paste_as_text'      => true,
            'relative_urls'      => false,
            'remove_script_host' => true,
            'entity_encoding'    => 'raw',
            //'toolbar_items_size' => 'small',
            'plugins' => [
                'advlist lists paste textcolor autoresize nonbreaking hr',
            ],
            'toolbar1' => 'undo redo removeformat | styleselect | bold italic | forecolor backcolor | '.
                          'alignleft aligncenter alignright alignjustify | bullist numlist | hr',
            'content_css' => $contentCss,
            'autoresize_max_height' => '500',
        ];

        // Front theme: for front usage
        $frontTheme = [
            'menubar'            => false,
            'statusbar'          => false,
            'resize'             => false,
            'image_advtab'       => true,
            'table_advtab'       => true,
            'paste_as_text'      => true,
            'relative_urls'      => false,
            'remove_script_host' => true,
            'entity_encoding'    => 'raw',
            //'toolbar_items_size' => 'small',
            'valid_elements'     => 'p,span,em,strong,br',
            'plugins' => [
                'paste autoresize',
            ],
            'toolbar1' => 'undo redo removeformat | bold italic',
            'content_css' => $contentCss,
            'autoresize_max_height' => '500',
        ];

        // Advanced theme with almost all enabled plugins
        $advancedTheme = [
            'menubar'            => false,
            'statusbar'          => true,
            'resize'             => false,
            'image_advtab'       => true,
            'table_advtab'       => true,
            'paste_as_text'      => true,
            'relative_urls'      => false,
            'remove_script_host' => true,
            'entity_encoding'  => 'raw',
            'image_class_list' => [
                ['title' => 'Aucun', 'value' => ''],
                ['title' => 'Responsive', 'value' => 'img-responsive'],
                ['title' => 'Flottant à gauche', 'value' => 'img-float-left'],
                ['title' => 'Flottant à droite', 'value' => 'img-float-right'],
            ],
            'plugins' => [
                'autoresize advlist anchor autolink lists link charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime nonbreaking save table contextmenu directionality',
                'paste textcolor image media imagetools', // emoticons template
            ],
            'toolbar1' => 'undo redo removeformat code | styleselect | table link anchor image media',
            'toolbar2' => 'bold italic underline strikethrough forecolor backcolor | alignleft aligncenter ' .
                          'alignright alignjustify | bullist numlist outdent indent | hr',
            'content_css' => $contentCss,
            'autoresize_max_height' => '500',

            'images_upload_url' => '/admin/tinymce/upload',
            //'images_upload_base_path' => '/some/basepath',
            'images_upload_credentials' => true,
        ];

        $styleFormatsMerge = (bool) $config['ui']['tinymce_formats_merge'];
        if (null === $styleFormats = $config['ui']['tinymce_formats']) {
            $styleFormats = $this->getDefaultFormats();
        } elseif ($styleFormatsMerge) {
            $styleFormats = array_merge($this->getDefaultFormats(), $styleFormats);
        }

        $externalPlugins = [];
        if (!in_array('EkynaMediaBundle', $bundles)) {
            $externalPlugins['filemanager'] = '/bundles/ekynamedia/js/tinymce.plugin.js';
        }

        if (is_array($styleFormats) && !empty($styleFormats)) {
            //$simpleTheme['style_formats_merge'] = $styleFormatsMerge;
            $simpleTheme['style_formats'] = $styleFormats;
            //$advancedTheme['style_formats_merge'] = $styleFormatsMerge;
            $advancedTheme['style_formats'] = $styleFormats;
        }

        if (!empty($externalPlugins)) {
            $advancedTheme['external_plugins'] = $externalPlugins;
        }

        return [
            'selector' => '.tinymce',
            'language' => '%locale%',
            'tinymce_url' => '/bundles/ekynacore/lib/tinymce',
            'theme' => [
                'simple'   => $simpleTheme,
                'advanced' => $advancedTheme,
                'front'    => $frontTheme,
            ],
        ];
    }

    private function getDefaultFormats()
    {
        // https://www.tinymce.com/docs/configure/content-formatting/
        return [
            [
                'title' => 'Titraille',
                'items' => [
                    ['title' => 'Titre 1', 'format' => 'h1'],
                    ['title' => 'Titre 2', 'format' => 'h2'],
                    ['title' => 'Titre 3', 'format' => 'h3'],
                    ['title' => 'Titre 4', 'format' => 'h4'],
                    ['title' => 'Titre 5', 'format' => 'h5'],
                    ['title' => 'Titre 6', 'format' => 'h6'],
                ],
            ],
            [
                'title' => 'En ligne',
                'items' => [
                    ['title' => 'Gras', 'icon' => 'bold', 'format' => 'bold'],
                    ['title' => 'Italique', 'icon' => 'italic', 'format' => 'italic'],
                    ['title' => 'Souligné', 'icon' => 'underline', 'format' => 'underline'],
                    ['title' => 'Batté', 'icon' => 'strikethrough', 'format' => 'strikethrough'],
                    ['title' => 'Exposant', 'icon' => 'superscript', 'format' => 'superscript'],
                    ['title' => 'Indice', 'icon' => 'subscript', 'format' => 'subscript'],
                    //['title' => 'Code', 'icon' => 'code', 'format' => 'code'],
                ],
            ],
            [
                'title' => 'Blocs',
                'items' => [
                    ['title' => 'Paragraphe', 'format' => 'p'],
                    ['title' => 'Citation', 'format' => 'blockquote'],
                    ['title' => 'Calque', 'format' => 'div'],
                    ['title' => 'Pré-formatté', 'format' => 'pre'],
                ],
            ],
        ];
    }
}
