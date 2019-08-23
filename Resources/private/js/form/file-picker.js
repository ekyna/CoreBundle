define(['jquery', 'ekyna-string'], function($) {
    "use strict";

    function FilePicker($element) {
        $element.data('FilePicker', this);

        this.$element = $element;
        this.$file = $element.find('input:file');
        this.$text = $element.find('input:text');

        this.current = this.$text.data('current') || null;

        this.$file.on('change', $.proxy(this.onFileChange, this));
        this.$text.on('click', $.proxy(this.onTextClick, this));

        $element.find('button[data-role="pick"]').on('click', $.proxy(this.onPickClick, this));
        $element.find('button[data-role="clear"]').on('click', $.proxy(this.onClearClick, this));
    }

    FilePicker.prototype.onFileChange = function() {
        var val = this.$file.val();

        if (0 < val.length) {
            this.$text.val(val.fileName());
        } else {
            this.$text.val(this.current);
        }

        this.$element.trigger($.Event('ekyna.upload.change'));

        return false;
    };

    FilePicker.prototype.onTextClick = function(e) {
        e.preventDefault();

        this.$file.trigger('click');

        return false;
    };

    FilePicker.prototype.onPickClick = function(e) {
        e.preventDefault();

        this.$file.trigger('click');

        return false;
    };

    FilePicker.prototype.onClearClick = function(e) {
        e.preventDefault();

        this.clear();

        this.$element.trigger($.Event('ekyna.upload.clear'));

        return false;
    };

    FilePicker.prototype.clear = function() {
        if (this.$file.files) {
            this.$file.files = [];
        }

        this.$text.val(this.current);
        this.$file.val(null).trigger('change');
    };

    $.fn.filePickerWidget = function() {
        this.each(function() {
            if (undefined === $(this).data('FilePicker')) {
                new FilePicker($(this));
            }
        });

        return this;
    };

    return {
        init: function($element) {
            $element.filePickerWidget();
        }
    };
});
