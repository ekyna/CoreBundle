module.exports = function (grunt, options) {
    return {
        core_fonts: {
            expand: true,
            cwd: 'bower_components/font-awesome/fonts',
            src: ['**'],
            dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/fonts'
        },
        core_fileupload: {
            src: 'bower_components/blueimp-file-upload/js/jquery.fileupload.js',
            dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/jquery/fileupload.js', // tmp to minify
            options: {
                process: function (content, srcpath) {
                    content = content.replace(/\.\/vendor\/jquery\.ui\.widget/g, 'jquery-ui/widget');
                    return content.replace(/jquery\.ui\.widget/g, 'jquery-ui/widget');
                }
            }
        },
        core_jquery: {
            files: [
                // jQuery
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
                }
                // TODO blueimp load image ?
            ]
        },
        core_jquery_ui: {
            expand: true,
            cwd: 'bower_components/jquery-ui/ui/minified',
            src: ['*.js'],
            dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/jquery-ui',
            rename: function(dest, src) {
                return dest + '/' + src.replace(/\.min\.js/, '.js');
            }
        },
        core_bootstrap: {
            files: [
                // Bootstrap
                {
                    src: 'bower_components/bootstrap/dist/js/bootstrap.min.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/bootstrap/bootstrap.js'
                },
                /*{
                    src: 'bower_components/bootstrap3-dialog/dist/js/bootstrap-dialog.min.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/bootstrap/dialog.js'
                },*/
                {
                    src: 'bower_components/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/bootstrap/hover-dropdown.js'
                },
                {
                    src: 'bower_components/bootstrap-colorpickersliders/dist/bootstrap.colorpickersliders.min.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/bootstrap/colorpicker.js'
                },
                {
                    src: 'bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/bootstrap/datetimepicker.js'
                }
            ]
        },
        core_bootstrap_dialog: {
            src: 'bower_components/bootstrap3-dialog/dist/js/bootstrap-dialog.min.js',
            dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/lib/bootstrap/dialog.js',
            options: {
                process: function (content, srcpath) {
                    return content.replace('"bootstrap-dialog",', '');
                }
            }
        },
        core_libs: {
            files: [
                // Others
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
                }
            ]
        },
        core_files: {
            expand: true,
            cwd: 'src/Ekyna/Bundle/CoreBundle/Resources/private',
            src: ['js/*.js', 'js/form/**', 'img/**'],
            dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public'
        }
    }
};
