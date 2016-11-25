define(['jquery', 'routing', 'select2'], function($, router) {
    "use strict";

    /**
     * Entity search widget
     */
    $.fn.entitySearchWidget = function(config) {

        config = $.extend({
            limit: 8
        }, config);

        this.each(function() {

            var $this = $(this);

            //var findUrl = router.generate($this.data('find'));
            var formatter = new Function('data', $this.data('format'));

            $this.select2({
                placeholder: 'Rechercher ...',
                allowClear: $this.data('clear') == 1,
                minimumInputLength: 3,
                templateResult: formatter,
                //templateSelection: formatter,
                ajax: {
                    delay: 300,
                    url: router.generate($this.data('search')),
                    dataType: 'json',
                    data: function (params) {
                        return {
                            query: params.term,
                            page:  params.page,
                            limit: config.limit
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: (params.page * config.limit) < data.total_count
                            }
                        };
                    },
                    escapeMarkup: function (markup) {
                        return markup;
                    }
                }
            });
        });
        return this;
    };

    return {
        init: function($element) {
            $element.entitySearchWidget();
        }
    };
});
