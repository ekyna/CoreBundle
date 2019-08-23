define(['jquery', 'jquery/fileupload', 'jquery/qtip', 'ekyna-form/file-picker', 'ekyna-string'], function ($) {
    "use strict";

    function RenameWidget($element, params) {
        $element.data('RenameWidget', this);

        params = $.extend({file: null}, params);

        this.$element = $element;
        this.$file = params.file;

        this.extension = '';
        this.defaultValue = this.$element.val();

        this.getExtension();

        if (this.$file && this.$file.length === 1) {
            this.$file.on('change', $.proxy(this.updateFromFile, this));
            this.updateFromFile();
        }

        this.$element.on('focus', $.proxy(this.stripExtension, this));
        this.$element.on('blur', $.proxy(this.normalize, this));
    }

    RenameWidget.prototype.getExtension = function () {
        var ext = this.$element.val().fileExtension();

        if (ext.length > 0) {
            this.extension = '.' + ext;
        }

        this.normalize();
    };

    RenameWidget.prototype.stripExtension = function () {
        if (this.extension.length === 0) {
            return;
        }

        var index = this.$element.val().lastIndexOf(this.extension);
        if (0 < index) {
            this.$element.val(this.$element.val().substring(0, index));
        }
    };

    RenameWidget.prototype.appendExtension = function () {
        this.$element.val(this.$element.val() + this.extension);
    };

    RenameWidget.prototype.normalize = function () {
        this.stripExtension();

        var value = this.$element.val().trim().toLowerCase().urlize();
        if (0 < value.length) {
            this.$element.val(value);
            this.appendExtension();
        } else {
            this.$element.val(this.defaultValue);
        }
    };

    RenameWidget.prototype.updateFromFile = function () {
        if (0 === this.$file.length) {
            return;
        }

        this.setFilename(this.$file.val());
    };

    RenameWidget.prototype.setFilename = function (value) {
        if (0 < value.length) {
            if (this.$element.val().length === 0) {
                this.$element.val(value.fileName());
            }

            var ext = value.fileName().fileExtension();
            if (ext.length > 0) {
                this.stripExtension();
                this.extension = '.' + ext;
                this.normalize();
            } else {
                this.getExtension();
            }
        } else {
            this.$element.val(this.defaultValue);
            this.getExtension();
        }
    };

    $.fn.renameWidget = function (params) {
        this.each(function () {
            if (undefined === $(this).data('RenameWidget')) {
                new RenameWidget($(this), params);
            }
        });

        return this;
    };


    function UploadWidget($element) {
        $element.data('UploadWidget', this);

        this.$element = $element;

        this.$picker = this.$element.find('.file-picker');
        this.$file = this.$picker.find('input:file');

        this.$rename = this.$element.find('.file-rename').renameWidget({file: this.$file});

        this.$key = this.$element.find('input[data-target="' + this.$file.attr('id') + '"]');

        if (0 === this.$key.length) {
            return;
        }

        this.$form = this.$file.closest('form');
        this.$progressBar = this.$element.find('div#' + this.$file.attr('id') + '_progress');
        this.$submitButton = this.$form.find('[type=submit]');

        this.uploadXhr = null;

        this.init();
    }

    UploadWidget.prototype.init = function() {
        this.$file.fileupload({
            replaceFileInput: false
        });
        this.$file.on('fileuploadadd', $.proxy(this.onUploadAdd, this));
        this.$file.on('fileuploadsubmit', $.proxy(this.onUploadSubmit, this));
        this.$file.on('fileuploadalways', $.proxy(this.onUploadAlways, this));
        this.$file.on('fileuploaddone', $.proxy(this.onUploadDone, this));
        this.$file.on('fileuploadprogress', $.proxy(this.onUploadProgress, this));

        this.$picker.on('ekyna.upload.clear', $.proxy(this.onPickerClear, this));
        this.$form.on('submit', $.proxy(this.onFormSubmit, this));
    };

    UploadWidget.prototype.onUploadAdd = function(e, data) {
        if (this.uploadXhr) {
            this.uploadXhr.abort();
        }

        console.log('uploadAdd', data);

        var name = data.originalFiles[0].name,
            picker = this.$picker.data('FilePicker'),
            rename = this.$rename.data('RenameWidget');

        picker.clear();
        picker.$text.val(name).trigger('change');
        rename.setFilename(name);

        this.uploadXhr = data.submit();
    };

    UploadWidget.prototype.onUploadSubmit = function () { //e, data
        var count = this.$form.data('uploadCount') || 0;
        count++;

        this.$submitButton.prop('disabled', true);
        this.$form.data('uploadCount', count);
        this.$progressBar.fadeIn();
    };

    UploadWidget.prototype.onUploadAlways = function () { // e, data
        var count = this.$form.data('uploadCount') || 0;
        count--;

        this.$form.data('uploadCount', count);

        if (0 >= count) {
            this.$submitButton.prop('disabled', false);
        }

        this.$progressBar.fadeOut(function () {
            $(this)
                .find('.progress-bar')
                .css({width: '0%'})
                .attr('aria-valuenow', 0);
        });

        this.uploadXhr = null;
    };

    UploadWidget.prototype.onUploadDone = function (e, data) {
        var result = JSON.parse(data.result);

        if (result.hasOwnProperty('upload_key')) {
            this.$key.val(result['upload_key']);
        }
    };

    UploadWidget.prototype.onUploadProgress = function (e, data) {
        if (!data._progress) {
            return;
        }

        var progress = parseInt(data._progress.loaded / data._progress.total * 100, 10);

        this.$progressBar
            .find('.progress-bar')
            .css({width: progress + '%'})
            .attr('aria-valuenow', progress);
    };

    UploadWidget.prototype.onPickerClear = function() {
        this.$key.val(null);

        if (this.uploadXhr) {
            this.uploadXhr.abort();
            this.uploadXhr = null;
        }
    };

    UploadWidget.prototype.onFormSubmit = function (e) {
        var count = this.$form.data('uploadCount') || 0;
        if (0 >= count) {
            return true;
        }

        this.$submitButton.qtip({
            content: 'Veuillez patienter pendant le téléchargement de vos fichiers&hellip;',
            style: {classes: 'qtip-bootstrap'},
            hide: {fixed: true, delay: 300},
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
    };

    /**
     * Upload widget
     */
    $.fn.uploadWidget = function () {
        this.each(function () {
            if (undefined === $(this).data('UploadWidget')) {
                new UploadWidget($(this));
            }
        });

        return this;
    };

    return {
        init: function ($element) {
            $element.uploadWidget();
        }
    };
});
