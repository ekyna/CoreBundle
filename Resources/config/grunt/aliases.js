module.exports = {
    'copy:core': [
        'copy:core_fontawesome',
        'copy:core_glyphicons',
        'copy:core_fileupload',
        'copy:core_jquery',
        'copy:core_jquery_ui',
        'copy:core_bootstrap',
        'copy:core_bootstrap_dialog',
        'copy:core_tinymce',
        'copy:core_libs',
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
