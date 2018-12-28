module.exports = function (grunt, options) {
    // @see https://github.com/gruntjs/grunt-contrib-less
    return {
        core: {
            files: {
                'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/glyphicons.css':
                    'src/Ekyna/Bundle/CoreBundle/Resources/private/less/glyphicons.less',
                'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/bootstrap.css':
                    'src/Ekyna/Bundle/CoreBundle/Resources/private/less/bootstrap.less',
                'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/ui.css':
                    'src/Ekyna/Bundle/CoreBundle/Resources/private/less/ui.less',
                'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/flags.css':
                    'src/Ekyna/Bundle/CoreBundle/Resources/private/less/flags.less'
            }
        }
    }
};
