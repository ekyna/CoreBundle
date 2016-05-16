module.exports = function (grunt, options) {
    return {
        core: {
            files: {
                'src/Ekyna/Bundle/CoreBundle/Resources/public/css/fontawesome.css': [
                    'bower_components/font-awesome/css/font-awesome.css'
                ],
                'src/Ekyna/Bundle/CoreBundle/Resources/public/css/content.css': [
                    'src/Ekyna/Bundle/CoreBundle/Resources/private/css/content.css'
                ],
                'src/Ekyna/Bundle/CoreBundle/Resources/public/css/tinymce-content.css': [
                    'src/Ekyna/Bundle/CoreBundle/Resources/private/css/tinymce-content.css'
                ],
                'src/Ekyna/Bundle/CoreBundle/Resources/public/css/form.css': [
                    'bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css',
                    'bower_components/bootstrap-colorpickersliders/dist/bootstrap.colorpickersliders.css',
                    'bower_components/select2/dist/css/select2.css',
                    'bower_components/qtip2/basic/jquery.qtip.css',
                    'src/Ekyna/Bundle/CoreBundle/Resources/private/css/form.css'
                ]
            }
        }
    }
};
