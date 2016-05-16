module.exports = function (grunt, options) {
    return {
        core: {
            files: {
                'src/Ekyna/Bundle/CoreBundle/Resources/public/js/ie/fix-10.js': [
                    'src/Ekyna/Bundle/CoreBundle/Resources/private/js/ie/ie10-viewport-bug-workaround.js'
                ],
                'src/Ekyna/Bundle/CoreBundle/Resources/public/js/ie/fix-9.js': [
                    'src/Ekyna/Bundle/CoreBundle/Resources/private/js/ie/html5shiv.min.js',
                    'src/Ekyna/Bundle/CoreBundle/Resources/private/js/ie/respond.min.js',
                    'src/Ekyna/Bundle/CoreBundle/Resources/private/js/ie/excanvas.min.js'
                ],
                'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/twig.js': [
                    'vendor/jms/twig-js/twig.js'
                ],
                'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/jquery/form.js': [
                    'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/jquery/form.js'
                ],
                'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/jquery/fileupload.js': [
                    'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/jquery/fileupload.js'
                ]
            }
        }
    }
};
