module.exports = function (grunt, options) {
    return {
        core: {
            options: {
                optimizationLevel: 6
            },
            files: [{
                expand: true,
                cwd: 'src/Ekyna/Bundle/CoreBundle/Resources/private/img/',
                src: ['**/*.{png,jpg,gif,svg}'],
                dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/img/'
            }]
        }
    }
};