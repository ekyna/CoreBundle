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

            /* Submit buttons */
            /*that.$elem.find('button[type="submit"]').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // TODO spin icon

                that.save();
            });*/

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
        var $tabs = $(event.target).eq(0).parents('.tab-pane');
        if ($tabs.size()) {
            showTabs($tabs);
            return;
        }
        event.preventDefault();
    });

    var $errorFields = $('form .has-error');
    if ($errorFields.size()) {
        showTabs($errorFields.eq(0).parents('.tab-pane'));
    }

    function showTabs($tabs) {
        $tabs.each(function() {
            var $a = $('a[href="#' + $(this).attr('id') + '"]');
            if ($a.size() == 1) {
                $a.tab('show');
            }
        });
    }

    return {
        create: function($element, options) {
            return new EkynaForm($element, options);
        }
    };
});
