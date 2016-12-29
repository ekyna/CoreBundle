module.exports = function (grunt, options) {
    return {
        core: {
            files: [
                {
                    src: 'src/Ekyna/Bundle/CoreBundle/Resources/private/ts/**/*.ts',
                    dest: 'src/Ekyna/Bundle/CoreBundle/Resources/public/tmp/js'
                }
            ],
            options: {
                fast: 'never',
                module: 'amd',
                rootDir: 'src/Ekyna/Bundle/CoreBundle/Resources/private/ts',
                noImplicitAny: false,
                removeComments: true,
                preserveConstEnums: true,
                sourceMap: false
            }
        }
    }
};
