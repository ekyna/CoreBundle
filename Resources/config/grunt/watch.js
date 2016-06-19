module.exports = function (grunt, options) {
    return {
        core_js: {
            files: ['src/Ekyna/Bundle/CoreBundle/Resources/private/js/**/*.js'],
            tasks: ['copy:core_js'],
            options: {
                spawn: false
            }
        }
    }
};
