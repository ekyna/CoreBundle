define(
    ['require', 'jquery', 'json!ekyna-form/plugins', 'autosize', 'select2', 'jquery/form'],
    function(require, $, plugins, autosize) {
    "use strict";

    $.fn.select2.defaults.set('theme', 'bootstrap');
    $.fn.select2.defaults.set('width', null);


    var EkynaForm = function ($elem, options) {
        this.$elem = $($elem);
        this.options = options;
    };

    EkynaForm.prototype = {
        constructor: EkynaForm,
        getElement: function() {
            return this.$elem;
        },
        init: function($parent) {
            //console.log('Form.init()', this.$elem, $parent);

            /* Textarea autosize */
            autosize(this.$elem.find('textarea').not('.tinymce'));

            /* Select2 */
            var select2options = {
                //selectOnClose: true // For tests
            };
            if ($parent && 1 === $parent.length) {
                select2options.dropdownParent = $parent;
            }
            this.$elem.find('select.select2').select2(select2options);

            /* Submit buttons */
            /*that.$elem.find('button[type="submit"]').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                // TODO spin icon
                that.save();
            });*/

            /* Plugins */
            var that = this;
            $.each(plugins, function (selector, paths) {
                var $target = that.$elem;
                if (!$target.is(selector)) {
                    $target = that.$elem.find(selector);
                }
                if (0 < $target.length) {
                    $.each(paths, function(i, path) {
                        require([path], function (plugin) {
                            plugin.init($target);
                        });
                    });
                }
            });

            this.$elem.data('form', this);
        },
        destroy: function() {

            /* Destroy textarea autosize */
            autosize.destroy(this.$elem.find('textarea').not('.tinymce'));

            /* Destroy select2 */
            this.$elem.find('select.select2').select2('destroy');

            var that = this;
            $.each(plugins, function (selector, paths) {
                var $target = that.$elem;
                if (!$target.is(selector)) {
                    $target = that.$elem.find(selector);
                }
                if (0 < $target.length) {
                    $.each(paths, function(i, path) {
                        require([path], function (plugin) {
                            if (plugin.hasOwnProperty('destroy')) {
                                plugin.destroy($target);
                            }
                        });
                    });
                }
            });
        },
        save: function() {
            var that = this;
            $.each(plugins, function (selector, paths) {
                var $target = that.$elem;
                if (!$target.is(selector)) {
                    $target = that.$elem.find(selector);
                }
                if (0 < $target.length) {
                    $.each(paths, function(i, path) {
                        require([path], function (plugin) {
                            if (plugin.hasOwnProperty('save')) {
                                plugin.save($target);
                            }
                        });
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
        if ($tabs.length) {
            showTabs($tabs);
            return;
        }
        event.preventDefault();
    });

    var $errorFields = $('form .has-error');
    if ($errorFields.length) {
        showTabs($errorFields.eq(0).parents('.tab-pane'));
    }

    function showTabs($tabs) {
        $tabs.each(function() {
            var $a = $('a[href="#' + $(this).attr('id') + '"]');
            if ($a.length === 1) {
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
