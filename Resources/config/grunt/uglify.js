module.exports = function (grunt, options) {
    return {
        core_js: {
            files: [
                {
                    'src/Ekyna/Bundle/CoreBundle/Resources/public/js/ie/fix-10.js': [
                        'src/Ekyna/Bundle/CoreBundle/Resources/private/js/ie/ie10-viewport-bug-workaround.js'
                    ],
                    'src/Ekyna/Bundle/CoreBundle/Resources/public/js/ie/fix-9.js': [
                        'src/Ekyna/Bundle/CoreBundle/Resources/private/js/ie/html5shiv.min.js',
                        'src/Ekyna/Bundle/CoreBundle/Resources/private/js/ie/respond.min.js',
                        'src/Ekyna/Bundle/CoreBundle/Resources/private/js/ie/excanvas.min.js'
                    ],
                    'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/jquery/form.js': [
                        'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/jquery/form.js'
                    ],
                    'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/jquery/fileupload.js': [
                        'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/lib/jquery/fileupload.js'
                    ],
                    'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/twig.js': [
                        'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/lib/twig.js'
                    ],
                    'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/aos.js': [
                        'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/lib/aos.js'
                    ]
                },
                {
                    expand: true,
                    cwd: 'src/Ekyna/Bundle/CoreBundle/Resources/private',
                    src: ['js/*.js', 'js/form/*.js'],
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public'
                }
            ]
        },
        core_ts: {
            files: [{
                expand: true,
                cwd: 'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/js',
                src: '**/*.js',
                dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/js'
            }]
        }
    }
};
