define('ekyna-form/color', ['jquery', 'bootstrap/colorpicker'], function($) {
    "use strict";

    return {
        init: function($element) {
            $element.each(function() {
                $(this).find('input[type="text"]').ColorPickerSliders($(this).data('options'));
            });
        }
    };
});
