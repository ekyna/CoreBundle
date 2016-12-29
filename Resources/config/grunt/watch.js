module.exports = function (grunt, options) {
    return {
        core_css: {
            files: ['src/Ekyna/Bundle/CoreBundle/Resources/private/css/**/*.css'],
            tasks: ['cssmin:core_content', 'cssmin:core_form'],
            options: {
                spawn: false
            }
        },
        core_less: {
            files: ['src/Ekyna/Bundle/CoreBundle/Resources/private/less/**/*.less'],
            tasks: ['less:core', 'copy:core_less', 'clean:core_less'],
            options: {
                spawn: false
            }
        },
        core_js: {
            files: ['src/Ekyna/Bundle/CoreBundle/Resources/private/js/**/*.js'],
            tasks: ['copy:core_js'],
            options: {
                spawn: false
            }
        },
        core_ts: {
            files: ['src/Ekyna/Bundle/CoreBundle/Resources/private/ts/**/*.ts'],
            tasks: ['ts:core', 'copy:core_ts', 'clean:core_ts'],
            options: {
                spawn: false
            }
        }
    }
};
