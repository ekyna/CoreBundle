define('ekyna-form/entity', ['jquery', 'ekyna-modal', 'ekyna-table'], function($, EkynaModal) {
    "use strict";

    $.fn.entityWidget = function(params) {

        params = $.extend({}, params);

        this.each(function() {

            var $entity = $(this);
            var $addButton = $entity.find('button.new-resource');
            var $listButton = $entity.find('button.list-resource');
            var $select = $entity.find('select');

            if ($addButton.length == 1) {
                $addButton.bind('click', function(e) {

                    var modal = new EkynaModal(), $form;
                    modal.load({url: $addButton.data('path')});

                    $(modal).on('ekyna.modal.content', function (e) {
                        if (e.contentType == 'form') {
                            $form = e.content;
                            $form.formWidget();
                        } else if (e.contentType == 'data') {
                            var data = e.content,
                                $option = $('<option />');
                            $option.prop('value', data.id);
                            $option.prop('selected', true);
                            if (data.name != undefined) {
                                $option.html(data.name);
                            } else if(data.title != undefined) {
                                $option.html(data.title);
                            } else {
                                throw "Unexpected resource data.";
                            }
                            $select.append($option).select2();
                            modal.getDialog().close();
                        } else {
                            throw "Unexpected modal content type";
                        }
                    });

                    $(modal).on('ekyna.modal.button_click', function (e) {
                        if (e.buttonId == 'submit') {
                            $form.ajaxSubmit({
                                dataType: 'xml',
                                success: function(response) {
                                    modal.handleResponse(response)
                                }
                            });
                        }
                    });
                });
            }

            if ($listButton.length == 1) {
                $listButton.bind('click', function(e) {
                    var modal = new EkynaModal();
                    modal.load({url: $listButton.data('path')});

                    $(modal).on('ekyna.modal.content', function (e) {
                        if (e.contentType == 'table') {
                            e.content.ekynaTable({
                                ajax: true,
                                onSelection: function(elements) {
                                    if ($select.prop('multiple')) {
                                        $select.find('option').prop('selected', false);
                                    }
                                    $(elements).each(function(index, element) {
                                        var $option = $select.find('option[value=' + element.id + ']');
                                        if ($option.length == 1) {
                                            $option.prop('selected', true);
                                        } else {
                                            $option = $('<option />');
                                            $option.prop('value', element.id);
                                            $option.prop('selected', true);
                                            if (element.name != undefined) {
                                                $option.html(element.name);
                                            } else if (element.title != undefined) {
                                                $option.html(element.title);
                                            } else {
                                                $option.html('Entity #' + element.id);
                                            }
                                            $select.append($option);
                                        }
                                    });
                                    $select.select2();
                                    modal.getDialog().close();
                                }
                            });
                        } else {
                            throw "Expected modal content type = 'table'.";
                        }
                    });
                });
            }
        });
        return this;
    };

    return {
        init: function($element) {
            $element.entityWidget();
        }
    };
});
