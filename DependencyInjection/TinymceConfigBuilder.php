<?php

namespace Ekyna\Bundle\CoreBundle\DependencyInjection;

/**
 * Class TinymceConfigBuilder
 * @package Ekyna\Bundle\CoreBundle\DependencyInjection
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class TinymceConfigBuilder
{
    /**
     * @var bool
     */
    private $isDevEnv = false;


    /**
     * Constructor.
     *
     * @param bool $isDevEnv
     */
    public function __construct($isDevEnv)
    {
        $this->isDevEnv = $isDevEnv;
    }

    /**
     * Builds the tinymce configuration.
     *
     * @param array $config
     * @param array $bundles
     *
     * @return array
     */
    public function build(array $config, array $bundles)
    {
        $contentCss = [];
        foreach ($config['ui']['stylesheets']['contents'] as $path) {
            $contentCss[] = preg_match('~^/?bundles~', $path) ? 'asset[' . $path . ']' : $path;
        }
        $contentCss[] = 'asset[bundles/ekynacore/css/tinymce-content.css]';
        foreach ($config['ui']['stylesheets']['fonts'] as $path) {
            $contentCss[] = $path;
        }

        // Simple theme: same as default theme
        $simpleTheme = [
            'branding'              => false,
            'menubar'               => false,
            'statusbar'             => false,
            'resize'                => false,
            'image_advtab'          => true,
            'table_advtab'          => true,
            'paste_as_text'         => true,
            'relative_urls'         => false,
            'remove_script_host'    => true,
            'entity_encoding'       => 'raw',
            //'toolbar_items_size' => 'small',
            'plugins'               => [
                'advlist lists paste textcolor autoresize nonbreaking hr',
            ],
            'toolbar1'              => 'undo redo removeformat | styleselect | bold italic | forecolor backcolor | ' .
                'alignleft aligncenter alignright alignjustify | bullist numlist | hr',
            'content_css'           => $contentCss,
            'autoresize_max_height' => '500',
        ];

        // Front theme: for front usage
        $frontTheme = [
            'branding'              => false,
            'menubar'               => false,
            'statusbar'             => false,
            'resize'                => false,
            'image_advtab'          => true,
            'table_advtab'          => true,
            'paste_as_text'         => true,
            'relative_urls'         => false,
            'remove_script_host'    => true,
            'entity_encoding'       => 'raw',
            //'toolbar_items_size' => 'small',
            'valid_elements'        => 'p,span,em,strong,br',
            'plugins'               => [
                'paste autoresize',
            ],
            'toolbar1'              => 'undo redo removeformat | bold italic',
            'content_css'           => $contentCss,
            'autoresize_max_height' => '500',
        ];
        /*if (!array_key_exists('front', $themes)) {
            $simpleTheme = array_merge($simpleTheme, $themes['front']);
        }*/

        // Advanced theme with almost all enabled plugins
        $advancedTheme = [
            'branding'              => false,
            'menubar'               => false,
            'statusbar'             => true,
            'resize'                => false,
            'image_advtab'          => true,
            'table_advtab'          => true,
            'paste_as_text'         => true,
            'relative_urls'         => false,
            'remove_script_host'    => true,
            'entity_encoding'       => 'raw',
            'image_class_list'      => [
                ['title' => 'Aucun', 'value' => ''],
                ['title' => 'Responsive', 'value' => 'img-responsive'],
                ['title' => 'Flottant à gauche', 'value' => 'img-float-left'],
                ['title' => 'Flottant à droite', 'value' => 'img-float-right'],
            ],
            'plugins'               => [
                'autoresize advlist anchor autolink lists link charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime nonbreaking save table contextmenu directionality',
                'paste textcolor image media imagetools', // emoticons template
            ],
            'toolbar1'              => 'undo redo removeformat code visualblocks | styleselect | table link anchor image media',
            'toolbar2'              => 'bold italic underline strikethrough forecolor backcolor | alignleft aligncenter ' .
                'alignright alignjustify | bullist numlist outdent indent | hr',
            'content_css'           => $contentCss,
            'autoresize_max_height' => '500',

            'images_upload_url'         => ($this->isDevEnv ? '/app_dev.php' : '') . '/admin/tinymce/upload',
            //'images_upload_base_path' => '/some/basepath',
            'images_reuse_filename'     => true,
            'images_upload_credentials' => true,
        ];

        // Color map
        if (!empty($config['ui']['colors'])) {
            $colorMap = [];
            foreach ($config['ui']['colors'] as $name => $hex) {
                $colorMap[] = $hex;
                $colorMap[] = $name;
            }
            $simpleTheme['textcolor_map'] = $colorMap;
            $advancedTheme['textcolor_map'] = $colorMap;
        }

        // Styles formats
        switch ($config['ui']['tinymce']['base_formats']) {
            case 'default' :
                $styleFormats = $this->getDefaultFormats();
                break;
            case 'bootstrap' :
                $styleFormats = $this->getBootstrapFormats();
                break;
            default :
                $styleFormats = [];
        }
        $customFormats = $config['ui']['tinymce']['custom_formats'];
        if (is_array($customFormats) && !empty($customFormats)) {
            $this->mergeFormats($styleFormats, $customFormats);
        }
        if (is_array($styleFormats) && !empty($styleFormats)) {
            $simpleTheme['style_formats'] = $styleFormats;
            $advancedTheme['style_formats'] = $styleFormats;
        }

        // External plugins
        $externalPlugins = [];
        if (isset($bundles['EkynaMediaBundle'])) {
            $externalPlugins['filemanager'] = '/bundles/ekynamedia/js/tinymce.plugin.js';
        }
        if (!empty($externalPlugins)) {
            $advancedTheme['external_plugins'] = $externalPlugins;
        }

        return [
            'selector'    => '.tinymce',
            'language'    => '%locale%',
            'tinymce_url' => '/bundles/ekynacore/lib/tinymce',
            'theme'       => [
                'simple'   => $simpleTheme,
                'advanced' => $advancedTheme,
                'front'    => $frontTheme,
            ],
        ];
    }

    /**
     * Merge the styles formats.
     *
     * @param array $a
     * @param array $b
     */
    private function mergeFormats(array &$a, array $b)
    {
        foreach ($b as $bIndex => $bFormat) {
            if (!isset($bFormat['title'])) {
                continue;
            }

            foreach ($a as $aIndex => &$aFormat) {
                // Match by title or skip
                if (!isset($aFormat['title']) || $aFormat['title'] != $bFormat['title']) {
                    continue;
                }

                // Does the format has children ?
                if (isset($bFormat['items'])) {
                    // Does the matching format has children too ?
                    if (isset($aFormat['items'])) {
                        // Merge children formats
                        $this->mergeFormats($aFormat['items'], $bFormat['items']);
                    } else {
                        throw new \InvalidArgumentException('Tinymce custom formats can\'t be merged.');
                    }
                } else {
                    // No children: replace all but title
                    foreach ($bFormat as $bKey => $bVal) {
                        if ($bKey != 'title') {
                            $aFormat[$bKey] = $bVal;
                        }
                    }
                    // Remove all keys of $aFormat that are not defined in $bFormat
                    foreach (array_diff(array_keys($aFormat), array_keys($bFormat)) as $key) {
                        unset($aFormat[$key]);
                    }
                }

                continue 2;
            }

            // Not found by title: append the format
            $a[] = $bFormat;
        }
    }

    /**
     * Returns the default styles formats.
     *
     * @return array
     */
    private function getDefaultFormats()
    {
        // https://www.tinymce.com/docs/configure/content-formatting/
        return [
            [
                'title' => 'Title',
                'items' => [
                    ['title' => 'Title 1', 'format' => 'h1'],
                    ['title' => 'Title 2', 'format' => 'h2'],
                    ['title' => 'Title 3', 'format' => 'h3'],
                    ['title' => 'Title 4', 'format' => 'h4'],
                    ['title' => 'Title 5', 'format' => 'h5'],
                    ['title' => 'Title 6', 'format' => 'h6'],
                ],
            ],
            [
                'title' => 'Block',
                'items' => [
                    ['title' => 'Paragraph', 'format' => 'p'],
                    ['title' => 'Block quote', 'format' => 'blockquote'],
                    ['title' => 'Layer', 'format' => 'div'],
                    ['title' => 'Pre formatted', 'format' => 'pre'],
                ],
            ],
            [
                'title' => 'Inline',
                'items' => [
                    ['title' => 'Bold', 'icon' => 'bold', 'format' => 'bold'],
                    ['title' => 'Italic', 'icon' => 'italic', 'format' => 'italic'],
                    ['title' => 'Underline', 'icon' => 'underline', 'format' => 'underline'],
                    ['title' => 'Strike through', 'icon' => 'strikethrough', 'format' => 'strikethrough'],
                    ['title' => 'Super script', 'icon' => 'superscript', 'format' => 'superscript'],
                    ['title' => 'Sub script', 'icon' => 'subscript', 'format' => 'subscript'],
                    //['title' => 'Code', 'icon' => 'code', 'format' => 'code'],
                ],
            ],
        ];
    }

    /**
     * Returns the bootstrap styles formats.
     *
     * @return array
     */
    private function getBootstrapFormats()
    {
        // https://www.bhavindoshi.com/blog/bootstrap-style-formats-in-tinymce-orchard-or-other-cms
        return [
            [
                'title' => 'Typography',
                'items' => [
                    [
                        'title' => 'Title',
                        'items' => [
                            ['title' => 'Title 1', 'format' => 'h1'],
                            ['title' => 'Title 2', 'format' => 'h2'],
                            ['title' => 'Title 3', 'format' => 'h3'],
                            ['title' => 'Title 4', 'format' => 'h4'],
                            ['title' => 'Title 5', 'format' => 'h5'],
                            ['title' => 'Title 6', 'format' => 'h6'],
                        ],
                    ],
                    [
                        'title' => 'Block',
                        'items' => [
                            ['title' => 'Paragraph', 'format' => 'p'],
                            ['title' => 'Lead paragraph', 'block' => 'p', 'classes' => 'lead'],
                            ['title' => 'Page header', 'block' => 'div', 'classes' => 'page-header', 'wrapper' => true],
                            ['title' => 'Block quote', 'format' => 'blockquote'],
                            ['title' => 'Blockquote reverse', 'block' => 'blockquote', 'classes' => 'blockquote-reverse'],
                            ['title' => 'Layer', 'format' => 'div'],
                            ['title' => 'Pre formatted', 'format' => 'pre'],
                            ['title' => 'Address', 'format' => 'address', 'wrapper' => true],
                        ],
                    ],
                    [
                        'title' => 'Inline',
                        'items' => [
                            ['title' => 'Bold', 'inline' => 'strong'],
                            ['title' => 'Italic', 'inline' => 'em'],
                            ['title' => 'Underline', 'inline' => 'u'],
                            ['title' => 'Super script', 'inline' => 'sup'],
                            ['title' => 'Sub script', 'inline' => 'sub'],
                            ['title' => 'Small', 'inline' => 'small'],
                            ['title' => 'Highlight', 'inline' => 'mark'],
                            ['title' => 'Deleted', 'inline' => 'del'],
                            ['title' => 'Strike through', 'inline' => 's'],
                            ['title' => 'Insert', 'inline' => 'ins'],
                        ],
                    ],
                    [
                        'title' => 'Alignment',
                        'items' => [
                            ['title' => 'Left aligned text', 'selector' => 'p,div,pre,h1,h2,h3,h4,h5,h6', 'classes' => 'text-left'],
                            ['title' => 'Center aligned text', 'selector' => 'p,div,pre,h1,h2,h3,h4,h5,h6', 'classes' => 'text-center'],
                            ['title' => 'Right aligned text', 'selector' => 'p,div,pre,h1,h2,h3,h4,h5,h6', 'classes' => 'text-right'],
                            ['title' => 'Justified text', 'selector' => 'p,div,pre', 'classes' => 'text-justify'],
                            ['title' => 'No wrap text', 'selector' => 'p,div,pre', 'classes' => 'text-nowrap'],
                        ],
                    ],
                    [
                        'title' => 'Transformations',
                        'items' => [
                            ['title' => 'lowercased text', 'selector' => 'p,h1,h2,h3,h4,h5,h6', 'classes' => 'text-lowercase'],
                            ['title' => 'UPPERCASED TEXT', 'selector' => 'p,h1,h2,h3,h4,h5,h6', 'classes' => 'text-uppercase'],
                            ['title' => 'Capitalized Text', 'selector' => 'p,h1,h2,h3,h4,h5,h6', 'classes' => 'text-capitalize'],
                        ],
                    ],
                    [
                        'title' => 'Abbreviations',
                        'items' => [
                            ['title' => 'Abbreviation', 'inline' => 'abbr'],
                            ['title' => 'Initialism', 'inline' => 'abbr', 'classes' => 'initialism'],
                        ],
                    ],
                    [
                        'title' => 'Lists',
                        'items' => [
                            ['title' => 'Unstyled', 'selector' => 'ul', 'classes' => 'list-unstyled'],
                            ['title' => 'Inline', 'selector' => 'ul', 'classes' => 'list-inline'],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Code',
                'items' => [
                    ['title' => 'Code Block', 'format' => 'pre', 'wrapper' => true],
                    ['title' => 'Code', 'inline' => 'code'],
                    ['title' => 'Sample', 'inline' => 'samp'],
                    ['title' => 'Keyboard', 'inline' => 'kbd'],
                    ['title' => 'Variable', 'inline' => 'var'],
                ],
            ],
            [
                'title' => 'Tables',
                'items' => [
                    ['title' => 'Table', 'selector' => 'table', 'classes' => 'table'],
                    ['title' => 'Striped', 'selector' => 'table', 'classes' => 'table-striped'],
                    ['title' => 'Bordered', 'selector' => 'table', 'classes' => 'table-bordered'],
                    ['title' => 'Hover', 'selector' => 'table', 'classes' => 'table-hover'],
                    ['title' => 'Condensed', 'selector' => 'table', 'classes' => 'table-condensed'],
                    ['title' => 'Active Row', 'selector' => 'tr', 'classes' => 'active'],
                    ['title' => 'Success Row', 'selector' => 'tr', 'classes' => 'success'],
                    ['title' => 'Info Row', 'selector' => 'tr', 'classes' => 'info'],
                    ['title' => 'Warning Row', 'selector' => 'tr', 'classes' => 'warning'],
                    ['title' => 'Danger Row', 'selector' => 'tr', 'classes' => 'danger'],
                    //['title' => 'Responsive Table', 'selector' => 'table', 'block' => 'div', 'classes' => 'table-responsive', 'wrapper' => true],
                ],
            ],
            [
                'title' => 'Buttons',
                'items' => [
                    ['title' => 'Default', 'selector' => 'a', 'classes' => 'btn btn-default'],
                    ['title' => 'Primary', 'selector' => 'a', 'classes' => 'btn btn-primary'],
                    ['title' => 'Success', 'selector' => 'a', 'classes' => 'btn btn-success'],
                    ['title' => 'Info', 'selector' => 'a', 'classes' => 'btn btn-info'],
                    ['title' => 'Warning', 'selector' => 'a', 'classes' => 'btn btn-warning'],
                    ['title' => 'Danger', 'selector' => 'a', 'classes' => 'btn btn-danger'],
                    ['title' => 'Link', 'selector' => 'a', 'classes' => 'btn btn-link'],
                ],
            ],
            [
                'title' => 'Images',
                'items' => [
                    ['title' => 'Responsive', 'selector' => 'img', 'classes' => 'img-responsive'],
                    ['title' => 'Rouded', 'selector' => 'img', 'classes' => 'img-rounded'],
                    ['title' => 'Circle', 'selector' => 'img', 'classes' => 'img-circle'],
                    ['title' => 'Thumbnail', 'selector' => 'img', 'classes' => 'img-thumbnail'],
                    ['title' => 'Flottant à gauche', 'selector' => 'img', 'classes' => 'img-float-left'],
                    ['title' => 'Flottant à droite', 'selector' => 'img', 'classes' => 'img-float-right'],
                ],
            ],
            [
                'title' => 'Utilities',
                'items' => [
                    ['title' => 'Muted Text', 'inline' => 'span', 'classes' => 'text-muted'],
                    ['title' => 'Primary Text', 'inline' => 'span', 'classes' => 'text-primary'],
                    ['title' => 'Success Text', 'inline' => 'span', 'classes' => 'text-success'],
                    ['title' => 'Info Text', 'inline' => 'span', 'classes' => 'text-info'],
                    ['title' => 'Warning Text', 'inline' => 'span', 'classes' => 'text-warning'],
                    ['title' => 'Danger Text', 'inline' => 'span', 'classes' => 'text-danger'],
                    ['title' => 'Background Primary', 'block' => 'div', 'classes' => 'bg-primary', 'wrapper' => true],
                    ['title' => 'Background Success', 'block' => 'div', 'classes' => 'bg-success', 'wrapper' => true],
                    ['title' => 'Background Info', 'block' => 'div', 'classes' => 'bg-info', 'wrapper' => true],
                    ['title' => 'Background Warning', 'block' => 'div', 'classes' => 'bg-warning', 'wrapper' => true],
                    ['title' => 'Background Danger', 'block' => 'div', 'classes' => 'bg-danger', 'wrapper' => true],
                    ['title' => 'Pull Left', 'block' => 'div', 'classes' => 'pull-left'],
                    ['title' => 'Pull Right', 'block' => 'div', 'classes' => 'pull-right'],
                    ['title' => 'Center Block', 'block' => 'div', 'classes' => 'center-block'],
                    ['title' => 'Clearfix', 'block' => 'div', 'classes' => 'clearfix'],

                ],
            ],
            /*[
                'title' => 'Nav Tabs/Pills',
                'items' => [
                    ['title' => 'Tabs (ul)', 'selector' => 'ul', 'classes' => 'nav nav-tabs'],
                    ['title' => 'Pills (ul)', 'selector' => 'ul', 'classes' => 'nav nav-pills'],
                    ['title' => 'Pills Stacked', 'selector' => 'ul', 'classes' => 'nav nav-pills nav-stacked'],
                ],
            ],*/
            [
                'title' => 'Labels',
                'items' => [
                    ['title' => 'Default', 'inline' => 'span', 'classes' => 'label label-default'],
                    ['title' => 'Primary', 'inline' => 'span', 'classes' => 'label label-primary'],
                    ['title' => 'Success', 'inline' => 'span', 'classes' => 'label label-success'],
                    ['title' => 'Info', 'inline' => 'span', 'classes' => 'label label-info'],
                    ['title' => 'Warning', 'inline' => 'span', 'classes' => 'label label-warning'],
                    ['title' => 'Danger', 'inline' => 'span', 'classes' => 'label label-danger'],
                ],
            ],
            [
                'title' => 'Alerts',
                'items' => [
                    ['title' => 'Success', 'block' => 'div', 'classes' => 'alert alert-success', 'wrapper' => true],
                    ['title' => 'Info', 'block' => 'div', 'classes' => 'alert alert-info', 'wrapper' => true],
                    ['title' => 'Warning', 'block' => 'div', 'classes' => 'alert alert-warning', 'wrapper' => true],
                    ['title' => 'Danger', 'block' => 'div', 'classes' => 'alert alert-danger', 'wrapper' => true],
                ],
            ],
            [
                'title' => 'Wells',
                'items' => [
                    ['title' => 'Well', 'block' => 'div', 'classes' => 'well', 'wrapper' => true],
                    ['title' => 'Large Well', 'block' => 'div', 'classes' => 'well well-lg', 'wrapper' => true],
                    ['title' => 'Small Well', 'block' => 'div', 'classes' => 'well well-sm', 'wrapper' => true],
                ],
            ],
        ];
    }
}
