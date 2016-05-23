module.exports = function (grunt, options) {
    // @see https://github.com/gruntjs/grunt-contrib-less
    return {
        core: {
            files: {
                'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/glyphicons.css':
                    'src/Ekyna/Bundle/CoreBundle/Resources/private/less/glyphicons.less'
            }
        }
    }
};
