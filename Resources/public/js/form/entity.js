define('ekyna-form/entity', ['jquery', 'ekyna-modal', 'ekyna-form', 'ekyna-table'], function($, Modal, Form, Table) {
    "use strict";

    $.fn.entityWidget = function() {

        this.each(function() {

            var $entity = $(this);
            var $addButton = $entity.find('button.new-resource');
            var $listButton = $entity.find('button.list-resource');
            var $select = $entity.find('select');

            if ($addButton.length == 1) {
                $addButton.bind('click', function() {

                    var modal = new Modal();
                    modal.load({url: $addButton.data('path')});

                    $(modal).on('ekyna.modal.content', function (e) {
                        if (e.contentType == 'data') {
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
                        }
                    });
                });
            }

            if ($listButton.length == 1) {
                $listButton.bind('click', function() {
                    var modal = new Modal();
                    modal.load({url: $listButton.data('path')});

                    $(modal).on('ekyna.modal.content', function (e) {
                        if (e.contentType == 'table') {
                            Table.create(e.content, {
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
