define(['jquery', 'select2'], function($) {
    "use strict";

    function formatIconOption(item) {
        if (!item.id) { return item.text; }
        return $(
            '<span><span class="fa fa-' + item.id + '"></span> ' + item.text + '</span>'
        );
    }

    return {
        init: function($element) {
            $element.select2({
                templateResult: formatIconOption,
                templateSelection: formatIconOption
            });
        }
    };
});
