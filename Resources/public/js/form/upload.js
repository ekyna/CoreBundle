define('ekyna-form/upload', ['jquery', 'jquery/fileupload', 'ekyna-string'], function($) {
    "use strict";

    /**
     * File widget
     */
    $.fn.filePicker = function(params) {

        params = $.extend({
            onChange: null,
            onClear: null
        }, params);

        this.each(function() {

            var $file = $(this).find('input:file');
            var $text = $(this).find('input:text');
            var current = $text.data('current') || null;
            var $pickButton = $(this).find('button[data-role="pick"]');
            var $clearButton = $(this).find('button[data-role="clear"]');

            var $key = $('input[data-target="' + $file.attr('id') + '"]');
            var $form = $file.closest('form');
            var uploadXhr = null;
            var $progressBar = $('div#' + $file.attr('id') + '_progress');

            $pickButton.unbind('click').bind('click', function(e) {
                e.preventDefault();
                $file.trigger('click');
            });

            $clearButton.unbind('click').bind('click', function(e) {
                e.preventDefault();
                if ($file.files) {
                    $file.files = [];
                }
                if ($key.length == 1) {
                    $key.val(null);
                }
                if (uploadXhr) {
                    uploadXhr.abort();
                }
                $file.val(null).trigger('change');
                if (typeof params.onClear === 'function') {
                    params.onClear($file);
                }
            }).trigger('click');

            $text.unbind('click').bind('click', function(e) {
                e.preventDefault();
                $file.trigger('click');
            });

            $file.unbind('change').bind('change', function() {
                if (uploadXhr) {
                    uploadXhr.abort();
                }
                var val = $file.val();
                if (0 < val.length) {
                    $text.val(val.fileName());
                } else {
                    $text.val(current);
                }
                if (typeof params.onChange === 'function') {
                    params.onChange(this);
                }
            });

            if ($key.length == 1) {
                $file
                    .fileupload()
                    .bind('fileuploadadd', function (e, data) {
                        uploadXhr = data.submit();
                    })
                    .bind('fileuploadsubmit', function () { //e, data
                        var count = $form.data('uploadCount') || 0;
                        count++;
                        $form.find('[type=submit]').prop('disabled', true);
                        $form.data('uploadCount', count);
                        $progressBar.fadeIn();
                    })
                    .bind('fileuploadalways', function () { // e, data
                        var count = $form.data('uploadCount') || 0;
                        count--;
                        $form.data('uploadCount', count);
                        if (0 >= count) {
                            $form.find('[type=submit]').prop('disabled', false);
                        }
                        $progressBar.fadeOut()
                            .find('.progress-bar')
                            .css({width: '0%'})
                            .attr('aria-valuenow', 0);
                        uploadXhr = null;
                    })
                    .bind('fileuploaddone', function (e, data) {
                        var result = JSON.parse(data.result);
                        if (result.hasOwnProperty('upload_key')) {
                            $key.val(result.upload_key);
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
                        $form.find('[type=submit]').qtip({
                            content: 'Veuillez patienter pendant le téléchargement de vos fichiers.',
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

    return {
        init: function($element) {
            var $file = $element.find('.file-picker').filePicker().find('input:file');
            $element.find('.file-rename').renameWidget({file: $file});
        }
    };
});
