module.exports = function (grunt, options) {
    return {
        core: {
            files: {
                'src/Ekyna/Bundle/CoreBundle/Resources/public/css/fontawesome.css': [
                    'bower_components/font-awesome/css/font-awesome.css'
                ],
                'src/Ekyna/Bundle/CoreBundle/Resources/public/css/glyphicons.css': [
                    'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/glyphicons.css'
                ],
                'src/Ekyna/Bundle/CoreBundle/Resources/public/css/content.css': [
                    'bower_components/bootstrap/dist/css/bootstrap.min.css',
                    'src/Ekyna/Bundle/CoreBundle/Resources/private/css/content.css'
                ],
                'src/Ekyna/Bundle/CoreBundle/Resources/public/css/tinymce-content.css': [
                    'src/Ekyna/Bundle/CoreBundle/Resources/private/css/tinymce-content.css'
                ],
                'src/Ekyna/Bundle/CoreBundle/Resources/public/css/core.css': [
                    'bower_components/bootstrap3-dialog/dist/css/bootstrap-dialog.css',
                    'bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css',
                    'bower_components/bootstrap-colorpickersliders/dist/bootstrap.colorpickersliders.css',
                    'bower_components/select2/dist/css/select2.css',
                    'bower_components/select2-bootstrap-theme/dist/select2-bootstrap.css',
                    'bower_components/qtip2/basic/jquery.qtip.css',
                    'src/Ekyna/Bundle/CoreBundle/Resources/private/css/form.css'
                ]
            }
        }
    }
};
