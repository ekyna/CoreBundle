define(['jquery', 'bootstrap/colorpicker'], function($) {
    "use strict";

    /** @see http://mjolnic.com/bootstrap-colorpicker/ */

    return {
        init: function($element) {
            $element.each(function() {
                var $input = $(this).find('input[type="text"]');

                $input.colorpicker($(this).data('options'));

                $input.on('changeColor', function(e) {
                    $input.css({backgroundColor: e.color.toHex()});
                });
            });
        }
    };
});
