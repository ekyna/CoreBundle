define('ekyna-form/entity-search', ['jquery', 'routing'], function($, router) {
    "use strict";

    /**
     * Entity search widget
     */
    $.fn.entitySearchWidget = function(params) {

        params = $.extend({
            limit: 8
        }, params);

        this.each(function() {

            var $this = $(this);

            var searchUrl = router.generate($this.data('search'));
            var findUrl = router.generate($this.data('find'));
            var allowClear = $this.data('clear') == 1;

            $this.select2({
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
