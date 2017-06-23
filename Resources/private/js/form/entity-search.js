define(['jquery', 'routing', 'select2'], function($, router) {
    "use strict";

    var defaults = {
        limit: 10,
        route: null,
        route_params: [],
        allow_clear: true
    };

    /**
     * Entity search widget
     */
    $.fn.entitySearchWidget = function() {

        this.each(function() {

            var $this = $(this),
                config = $.extend({}, defaults, $this.data('config'));

            function initSelect2() {
                $this.select2({
                    allowClear: config.allow_clear,
                    minimumInputLength: 3,
                    ajax: {
                        delay: 300,
                        url: router.generate(config.route, config.route_params),
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
            }

            initSelect2();
        });
        return this;
    };

    return {
        init: function($element) {
            $element.entitySearchWidget();
        }
    };
});
