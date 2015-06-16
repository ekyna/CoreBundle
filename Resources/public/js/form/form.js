define(
    'ekyna-form',
    ['require', 'jquery', 'json!ekyna-form/plugins', 'select2', 'malsup/form', 'jquery/autosize'],
    function(require, $, config) {
    "use strict";

    var EkynaForm = function () {
        this.plugins = config;
    };

    EkynaForm.prototype = {
        constructor: EkynaForm,
        init: function(formSelector) {
            var that = this;
            var $forms = $(formSelector);
            if ($forms.size() > 0) {
                $forms.each(function () {
                    var $form = $(this);

                    /* Textarea autosize */
                    $form.find('textarea').not('.tinymce').autosize({append: "\n"});

                    /* Select2 */
                    $form.find('select').not('.no-select2').each(function () {
                        $(this).select2({
                            allowClear: ($(this).data('allow-clear') == 1)
                        });
                    });

                    $(that.plugins).each(function (i, config) {
                        var $target = $form.find(config.selector);
                        if ($target.size() > 0) {
                            require([config.path], function (plugin) {
                                plugin.init($target);
                            });
                        }
                    });
                });
            }
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

    /**
     * Tinymce modal fix
     * thanks @harry: http://stackoverflow.com/questions/18111582/tinymce-4-links-plugin-modal-in-not-editable
     * @see http://jsfiddle.net/e99xf/13/
     */
    $(document).on('focusin', function(e) {
        if ($(e.target).closest(".mce-window").length) {
            e.stopImmediatePropagation();
        }
    });

    window.EkynaForm = EkynaForm;

    return new EkynaForm;
});
