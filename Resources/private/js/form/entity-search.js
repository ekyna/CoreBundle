define(['jquery', 'routing'], function($, router) {
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

            var searchUrl = router.generate($this.data('search'));
            var findUrl = router.generate($this.data('find'));
            var allowClear = $this.data('clear') == 1;

            $this.select2({
                placeholder: 'Rechercher ...',
                allowClear: allowClear,
                minimumInputLength: 3,
                ajax: {
                    delay: 300,
                    url: searchUrl,
                    dataType: 'json',
                    data: function (params) {
                        return {
                            search: params.term, // search term
                            page: params.page,
                            limit: config.limit
                        };
                    },
                    processResults: function (data, params) {
                        console.log(data);

                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;

                        return {
                            results: data.items,
                            pagination: {
                                more: (params.page * config.limit) < data.total_count
                            }
                        };
                    },
                    escapeMarkup: function (markup) { return markup; }
                }
            });

            /*$this.select2({
                placeholder: 'Rechercher ...',
                minimumInputLength: 0,
                allowClear: allowClear,
                ajax: {
                    quietMillis: 300,
                    url: searchUrl,
                    dataType: 'jsonp',
                    data: function (term) { // , page
                        return {
                            limit: params.limit,
                            search: term
                        };
                    },
                    results: function (data) { // , page
                        return { results: data.results };
                    }
                },
                initSelection : function (element, callback) {
                    var id = parseInt(element.val());
                    if(id > 0) {
                        $.ajax({
                            url: findUrl,
                            data: {id: id},
                            dataType: 'json'
                        })
                        .done(function(data) {
                            callback(data);
                        });
                    }
                }
            });*/
        });
        return this;
    };

    return {
        init: function($element) {
            $element.entitySearchWidget();
        }
    };
});
