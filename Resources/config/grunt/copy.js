module.exports = function (grunt, options) {
    return {
        core_fonts: {
            files: [
                // Fontawesome
                {
                    expand: true,
                    cwd: 'bower_components/font-awesome/fonts',
                    src: ['**'],
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/fonts'
                },
                // Glyphicons
                {
                    expand: true,
                    cwd: 'bower_components/bootstrap/dist/fonts',
                    src: ['**'],
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/fonts'
                }
            ]
        },
        core_libs: {
            files: [
                // Jquery
                {
                    src: 'bower_components/jquery/dist/jquery.min.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/jquery/jquery.js'
                },
                {
                    src: 'bower_components/matchHeight/dist/jquery.matchHeight-min.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/jquery/match-height.js'
                },
                {
                    src: 'bower_components/qtip2/jquery.qtip.min.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/jquery/qtip.js'
                },
                {
                    src: 'bower_components/jquery-form/jquery.form.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/jquery/form.js' // tmp to minify
                },
                // Jquery Ui
                {
                    expand: true,
                    cwd: 'bower_components/jquery-ui/ui/minified',
                    src: ['*.js'],
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/jquery-ui',
                    rename: function(dest, src) {
                        return dest + '/' + src.replace(/\.min\.js/, '.js');
                    }
                },
                {
                    expand: true,
                    cwd: 'bower_components/jquery-ui/themes/smoothness/images',
                    src: ['**'],
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/css/images'
                },
                // Bootstrap
                {
                    src: 'bower_components/bootstrap/dist/js/bootstrap.min.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/bootstrap/bootstrap.js'
                },
                {
                    src: 'bower_components/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/bootstrap/hover-dropdown.js'
                },
                {
                    src: 'bower_components/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/bootstrap/colorpicker.js'
                },
                {
                    src: 'bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/bootstrap/datetimepicker.js'
                },
                {
                    expand: true,
                    cwd: 'bower_components/mjolnic-bootstrap-colorpicker/dist/img',
                    src: ['**'],
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/img'
                },
                // Others
                {
                    src: 'node_modules/twig/twig.min.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/twig.js'
                },
                {
                    src: 'bower_components/moment/min/moment-with-locales.min.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/moment.js'
                },
                {
                    src: 'bower_components/autosize/dist/autosize.min.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/autosize.js'
                },
                {
                    src: 'bower_components/select2/dist/js/select2.min.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/select2.js'
                },
                {
                    src: 'bower_components/tinycolor/dist/tinycolor-min.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/tinycolor.js'
                },
                {
                    src: 'bower_components/es6-promise/es6-promise.min.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/es6-promise.js'
                },
                {
                    src: 'bower_components/backbone/backbone-min.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/backbone.js'
                },
                {
                    src: 'bower_components/underscore/underscore-min.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/underscore.js'
                },
                {
                    expand: true,
                    cwd: 'bower_components/tinymce',
                    src: [
                        'plugins/**/*.min.js',
                        'plugins/**/*.css',
                        'plugins/**/*.swf',
                        'plugins/**/*.gif',
                        'skins/**',
                        'themes/**',
                        'tinymce.min.js'
                    ],
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/tinymce'
                },
                {
                    src: 'bower_components/aos/dist/aos.css',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/css/aos.css'
                },
                {
                    src: 'bower_components/aos/dist/aos.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/aos.js'
                }
            ]
        },
        core_libs_fix: {
            files: [
                {
                    src: 'bower_components/blueimp-file-upload/js/jquery.fileupload.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/jquery/fileupload.js' // tmp to minify
                },
                {
                    src: 'bower_components/bootstrap3-dialog/dist/js/bootstrap-dialog.min.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/bootstrap/dialog.js'
                }
            ],
            options: {
                process: function (content, srcpath) {
                    // Bootstrap Dialog
                    if (/bootstrap-dialog/.test(srcpath)) {
                        content = content.replace('"bootstrap-dialog",', '');
                    }
                    // jQuery FileUpload
                    if (/jquery\.fileupload/.test(srcpath)) {
                        content = content.replace(/\.\/vendor\/jquery\.ui\.widget/g, 'jquery-ui/widget');
                        content = content.replace(/jquery\.ui\.widget/g, 'jquery-ui/widget');
                    }

                    return content;
                }
            }
        },
        core_files: {
            expand: true,
            cwd: 'src/Ekyna/Bundle/CoreBundle/Resources/private',
            src: ['img/**', 'lib/**'],
            dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public'
        },
        core_less: { // For watch:core_less
            files: [
                {
                    expand: true,
                    cwd: 'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/css',
                    src: ['**'],
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/css'
                }
            ]
        },
        core_ts: { // For watch:core_ts
            files: [
                {
                    expand: true,
                    cwd: 'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/js',
                    src: ['**/*.js'],
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/js'
                }
            ]
        },
        core_js: { // For watch:core_js
            files: [
                {
                    expand: true,
                    cwd: 'src/Ekyna/Bundle/CoreBundle/Resources/private/js',
                    src: ['**/*.js'],
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/js'
                }
            ]
        }
    }
};
