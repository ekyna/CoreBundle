module.exports = {
    'copy:core': [
        'copy:core_fonts',
        'copy:core_libs',
        'copy:core_fileupload',
        'copy:core_contextmenu',
        'copy:core_bootstrap'
    ],
    'cssmin:core': [
        'cssmin:core_lib',
        'cssmin:core_content',
        'cssmin:core_form'
    ],
    'build:core_js': [
        'ts:core',
        'uglify:core_ts',
        'uglify:core_js',
        'clean:core_ts'
    ],
    'build:core': [
        'clean:core_pre',
        'copy:core',
        'imagemin:core',
        'less:core',
        'cssmin:core',
        'build:core_js',
        'clean:core_post'
    ]
};
