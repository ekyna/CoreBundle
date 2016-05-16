define('ekyna-form/parent-choice', ['jquery', 'routing'], function($, router) {
    "use strict";

    /**
     * Choice parent selector
     */
    var FormChoiceParentSelector = function(elem){
        this.elem = elem;
        this.$elem = $(elem);
        this.$parent = null;
        this.metadata = this.$elem.data('parent-choice');
    };

    FormChoiceParentSelector.prototype = {
        defaults: {},
        init: function() {
            this.config = $.extend({}, this.defaults, this.metadata);
            this.$parent = $('select#' + this.config.field);
            var t = this;
            if (this.$parent.length > 0) {
                this.$parent.bind('change', function() {
                    t.updateChoices()
                });
                var value = parseInt(this.$elem.val());
                if (!value) {
                    this.$parent.trigger('change');
                }
            }
            return this;
        },
        updateChoices: function() {
            var $select = this.$elem;
            if (this.$parent.prop('disabled')) {
                return;
            }
            var parentId = parseInt(this.$parent.val());
            if (!parentId) {
                return;
            }
            var $defaultOption = $select.find('option').eq(0);
            $select.empty().append($defaultOption).prop('disabled', true);
            var xhr = $.get(router.generate(this.config.route, {'id': parentId}));
            xhr.done(function(data) {
                if (typeof data.choices !== 'undefined') {
                    if ($(data.choices).length > 0) {
                        $(data.choices).each(function (index, choice) {
                            var $option = $('<option />');
                            $option.attr('value', choice.value).text(choice.text);
                            $select.append($option);
                        });
                        $select.prop('disabled', false);
                    }
                }
                $select.trigger('form_choices_loaded', data);
            });
        }
    };

    FormChoiceParentSelector.defaults = FormChoiceParentSelector.prototype.defaults;

    $.fn.formChoiceParentSelectorWidget = function(options) {
        return this.each(function() {
            new FormChoiceParentSelector(this, options).init();
        });
    };

    return {
        init: function($element) {
            $element.each(function() {
                new FormChoiceParentSelector(this).init();
            });
        }
    };
});
