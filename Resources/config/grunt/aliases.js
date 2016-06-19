module.exports = {
    'copy:core': [
        'copy:core_fonts',
        'copy:core_libs',
        'copy:core_libs_fix',
        'copy:core_files'
    ],
    'build:core': [
        'clean:core_pre',
        'copy:core',
        'less:core',
        'cssmin:core',
        'uglify:core',
        'clean:core_post'
    ]
};
