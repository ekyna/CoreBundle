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
                    'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/jquery/fileupload.js': [
                        'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/lib/jquery/fileupload.js'
                    ]
                },
                {
                    expand: true,
                    cwd: 'src/Ekyna/Bundle/CoreBundle/Resources/private',
                    src: ['js/*.js', 'js/form/*.js'],
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public'
                },
                {
                    expand: true,
                    cwd: 'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/jquery-ui',
                    src: ['**/*.js'],
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/jquery-ui'
                }
            ]
        },
        core_ts: {
            expand: true,
            cwd: 'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/js',
            src: '**/*.js',
            dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/js'
        }
    }
};
