define(
    'ekyna-form',
    ['require', 'jquery', 'json!ekyna-form/plugins', 'select2', 'malsup/form', 'jquery/autosize'],
    function(require, $, plugins) {
    "use strict";

    var EkynaForm = function ($elem, options) {
        this.$elem = typeof $elem == 'jQuery' ? $elem : $($elem);
        this.options = options;
    };

    EkynaForm.prototype = {
        constructor: EkynaForm,
        getElement: function() {
            return this.$elem;
        },
        init: function() {
            var that = this;

            /* Textarea autosize */
            that.$elem.find('textarea').not('.tinymce').autosize({append: "\n"});

            /* Select2 */
            that.$elem.find('select').not('.no-select2').each(function () {
                $(this).select2({
                    allowClear: ($(this).data('allow-clear') == 1)
                });
            });

            /* Plugins */
            $(plugins).each(function (i, config) {
                var $target = that.$elem.find(config.selector);
                if ($target.length > 0) {
                    require([config.path], function (plugin) {
                        plugin.init($target);
                    });
                }
            });
        },
        destroy: function() {
            var that = this;
            $(plugins).each(function (i, config) {
                var $target = that.$elem.find(config.selector);
                if ($target.length > 0) {
                    require([config.path], function (plugin) {
                        if (plugin.hasOwnProperty('destroy')) {
                            plugin.destroy($target);
                        }
                    });
                }
            });
        },
        save: function() {
            var that = this;
            $(plugins).each(function (i, config) {
                var $target = that.$elem.find(config.selector);
                if ($target.length > 0) {
                    require([config.path], function (plugin) {
                        if (plugin.hasOwnProperty('save')) {
                            plugin.save($target);
                        }
                    });
                }
            });
        }
    };

    /**
     * Form with tabs error handler
     * @see http://jsfiddle.net/GJeez/8/
     * @see http://www.html5rocks.com/en/tutorials/forms/constraintvalidation/?redirect_from_locale=fr#toc-checkValidity
     */
    $(".form-with-tabs input, .form-with-tabs textarea, .form-with-tabs select").on('invalid', function(event) {
        var $tab = $(event.target).parents('.tab-pane').eq(0);
        if ($tab.length == 1) {
            var $a = $('a[href="#' + $tab.attr('id') + '"]');
            if ($a.length == 1) {
                $a.tab('show');
                return;
            }
        }
        event.preventDefault();
    });

    return {
        create: function($element, options) {
            var form = new EkynaForm($element, options);
            form.init();
            return form;
        }
    };
});
