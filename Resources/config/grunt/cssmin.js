module.exports = function (grunt, options) {
    return {
        core_lib: {
            files: {
                'src/Ekyna/Bundle/CoreBundle/Resources/public/css/glyphicons.css': [
                    'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/glyphicons.css'
                ],
                'src/Ekyna/Bundle/CoreBundle/Resources/public/css/bootstrap.css': [
                    'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/bootstrap.css'
                ],
                'src/Ekyna/Bundle/CoreBundle/Resources/public/css/ui.css': [
                    'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/ui.css'
                ],
                'src/Ekyna/Bundle/CoreBundle/Resources/public/css/jquery-ui.css': [
                    'node_modules/jquery-ui-themes/themes/smoothness/jquery-ui.css',
                    'src/Ekyna/Bundle/CoreBundle/Resources/private/css/jquery-ui.css'
                ]
            }
        },
        core_content: {
            files: {
                'src/Ekyna/Bundle/CoreBundle/Resources/public/css/content.css': [
                    'node_modules/bootstrap/dist/css/bootstrap.css',
                    'src/Ekyna/Bundle/CoreBundle/Resources/private/css/content.css'
                ],
                'src/Ekyna/Bundle/CoreBundle/Resources/public/css/tinymce-content.css': [
                    'src/Ekyna/Bundle/CoreBundle/Resources/private/css/tinymce-content.css'
                ]
            }
        },
        core_form: {
            files: {
                'src/Ekyna/Bundle/CoreBundle/Resources/public/css/form.css': [
                    'node_modules/bootstrap3-dialog/dist/css/bootstrap-dialog.css',
                    'node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css',
                    'node_modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css',
                    'node_modules/select2/dist/css/select2.css',
                    'node_modules/select2-bootstrap-theme/dist/select2-bootstrap.css',
                    'node_modules/qtip2/dist/jquery.qtip.css',
                    'src/Ekyna/Bundle/CoreBundle/Resources/private/css/form.css'
                ]
            }
        }
    }
};
