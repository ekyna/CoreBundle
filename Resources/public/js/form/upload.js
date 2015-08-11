define('ekyna-form/upload', ['jquery', 'jquery/fileupload', 'jquery/qtip', 'ekyna-form/file-picker', 'ekyna-string'], function($) {
    "use strict";

    /**
     * Rename widget
     */
    $.fn.renameWidget = function(params) {

        params = $.extend({file: null}, params);

        this.each(function() {

            var $rename = $(this);
            var $file = params.file;
            var extension = '';
            var defaultValue = $rename.val();

            $rename.stripExtension = function() {
                if(extension.length == 0) return;
                var extension_index = $rename.val().lastIndexOf(extension);
                if(extension_index > 0) {
                    $rename.val($rename.val().substring(0, extension_index));
                }
            };

            $rename.appendExtension = function() {
                $rename.val($rename.val() + extension);
            };

            $rename.normalize = function() {
                $rename.stripExtension();
                var value = $rename.val().trim().urlize();
                if(value.length > 0) {
                    $rename.val(value);
                    $rename.appendExtension();
                }else{
                    $rename.val(defaultValue);
                }
            };

            $rename.getExtension = function() {
                var ext = $rename.val().fileExtension();
                if(ext.length > 0) {
                    extension = '.'+ext;
                }
                $rename.normalize();
            };
            $rename.getExtension();

            if ($file !== null && $file.length == 1) {
                $rename.updateFromFile = function() {
                    var fileVal = $file.val();
                    if (0 < fileVal.length) {
                        if ($rename.val().length == 0) {
                            $rename.val(fileVal.fileName());
                        }
                        var ext = fileVal.fileName().fileExtension();
                        if (ext.length > 0) {
                            $rename.stripExtension();
                            extension = '.'+ext;
                            $rename.normalize();
                        } else {
                            $rename.getExtension();
                        }
                    } else {
                        $rename.val(defaultValue);
                        $rename.getExtension();
                    }
                };
                $file.bind('change', $rename.updateFromFile);
                $rename.updateFromFile();
            }

            $rename.bind('focus', function() {
                $rename.stripExtension();
            });

            $rename.bind('blur', $rename.normalize);
        });

        return this;
    };

    /**
     * Upload widget
     */
    $.fn.uploadWidget = function() {

        this.each(function() {
            var $this = $(this);

            var $filePicker = $this.find('.file-picker').filePickerWidget();

            var $file = $filePicker.find('input:file');
            $this.find('.file-rename').renameWidget({file: $file});

            var $key = $this.find('input[data-target="' + $file.attr('id') + '"]');

            if ($key.length == 1) {
                var uploadXhr = null;

                var $form = $file.closest('form');
                var $progressBar = $this.find('div#' + $file.attr('id') + '_progress');
                var $submitButton = $form.find('[type=submit]');
                if ($submitButton.length == 0) {
                    $submitButton = $form.closest('.modal-content').find('button#submit'); // For modals
                }

                $filePicker.on('ekyna.upload.clear', function() {
                    $key.val(null);
                    if (uploadXhr) {
                        uploadXhr.abort();
                        uploadXhr = null;
                    }
                });

                $file
                    .fileupload()
                    .bind('fileuploadadd', function (e, data) {
                        if (uploadXhr) {
                            uploadXhr.abort();
                        }
                        uploadXhr = data.submit();
                    })
                    .bind('fileuploadsubmit', function () { //e, data
                        var count = $form.data('uploadCount') || 0;
                        count++;
                        $submitButton.prop('disabled', true);
                        $form.data('uploadCount', count);
                        $progressBar.fadeIn();
                    })
                    .bind('fileuploadalways', function () { // e, data
                        var count = $form.data('uploadCount') || 0;
                        count--;
                        $form.data('uploadCount', count);
                        if (0 >= count) {
                            $submitButton.prop('disabled', false);
                        }
                        $progressBar.fadeOut(function() {
                            $(this)
                                .find('.progress-bar')
                                .css({width: '0%'})
                                .attr('aria-valuenow', 0);
                        });
                        uploadXhr = null;
                    })
                    .bind('fileuploaddone', function (e, data) {
                        var result = JSON.parse(data.result);
                        if (result.hasOwnProperty('upload_key')) {
                            $key.val(result['upload_key']);
                        }
                    })
                    .bind('fileuploadprogress', function (e, data) {
                        if (data._progress) {
                            var progress = parseInt(data._progress.loaded / data._progress.total * 100, 10);
                            $progressBar
                                .find('.progress-bar')
                                .css({width: progress + '%'})
                                .attr('aria-valuenow', progress);
                        }
                    })
                ;

                $form.bind('submit', function(e) {
                    var count = $form.data('uploadCount') || 0;
                    if (0 < count) {
                        $submitButton.qtip({
                            content: 'Veuillez patienter pendant le téléchargement de vos fichiers&hellip;',
                            style: { classes: 'qtip-bootstrap' },
                            hide: { fixed: true, delay: 300 },
                            position: {
                                my: 'bottom center',
                                at: 'top center',
                                target: 'mouse',
                                adjust: {
                                    mouse: false,
                                    scroll: false
                                }
                            }
                        });
                        e.preventDefault();
                        return false;
                    }
                });
            }
        });

        return this;
    };

    return {
        init: function($element) {
            $element.uploadWidget();
        }
    };
});
