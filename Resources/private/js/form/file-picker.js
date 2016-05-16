define('ekyna-form/file-picker', ['jquery', 'ekyna-string'], function($) {
    "use strict";

    /**
     * File picker widget
     */
    $.fn.filePickerWidget = function() {

        this.each(function() {
            var $this = $(this);
            var $file = $this.find('input:file');
            var $text = $this.find('input:text');
            var current = $text.data('current') || null;
            var $pickButton = $this.find('button[data-role="pick"]');
            var $clearButton = $this.find('button[data-role="clear"]');

            $pickButton.unbind('click').bind('click', function(e) {
                e.preventDefault();
                $file.trigger('click');
            });

            $clearButton.unbind('click').bind('click', function(e) {
                e.preventDefault();
                if ($file.files) {
                    $file.files = [];
                }
                $text.val(current);
                $file.val(null).trigger('change');

                $this.trigger(jQuery.Event('ekyna.upload.clear'));
            }).trigger('click');

            $text.unbind('click').bind('click', function(e) {
                e.preventDefault();
                $file.trigger('click');
            });

            $file.unbind('change').bind('change', function() {
                var val = $file.val();
                if (0 < val.length) {
                    $text.val(val.fileName());
                } else {
                    $text.val(current);
                }

                $this.trigger(jQuery.Event('ekyna.upload.change'));
            });
        });

        return this;
    };

    return {
        init: function($element) {
            $element.filePickerWidget();
        }
    };
});
