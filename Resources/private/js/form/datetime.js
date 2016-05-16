define('ekyna-form/datetime', ['jquery', 'bootstrap/datetimepicker'], function($) {
    "use strict";

    return {
        init: function($element) {
            $element.each(function() {
                $(this).datetimepicker($(this).data('options'));
            });
        }
    };
});
