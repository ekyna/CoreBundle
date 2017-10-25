define(['jquery', 'ekyna-modal', 'ekyna-table', 'select2'], function($, Modal, Table) {
    "use strict";

    $.fn.entityWidget = function() {

        this.each(function() {

            var $entity = $(this),
                $addButton = $entity.find('button.new-resource'),
                $listButton = $entity.find('button.list-resource'),
                $select = $entity.find('select');

            if ($addButton.length === 1) {
                $addButton.bind('click', function() {

                    var modal = new Modal();
                    modal.load({url: $addButton.data('path')});

                    $(modal).on('ekyna.modal.response', function (modalEvent) {
                        if (modalEvent.contentType === 'json') {
                            modalEvent.preventDefault();

                            var data = modalEvent.content,
                                $option = $('<option />');
                            $option.prop('value', data.id);
                            $option.prop('selected', true);
                            if (data.choice_label !== undefined) {
                                $option.html(data.choice_label);
                            } else if (data.name !== undefined) {
                                $option.html(data.name);
                            } else if(data.title !== undefined) {
                                $option.html(data.title);
                            } else {
                                throw "Unexpected resource data.";
                            }
                            // TODO Needed ? More options
                            /*$select.append($option).select2({
                                selectOnClose: true // For tests ...
                            });*/
                            $select.find('option').prop('selected', false);
                            // TODO attach data when response will be the serialized entity
                            $select.append($option).trigger('change');

                            modalEvent.modal.close();
                        }
                    });
                });
            }

            if ($listButton.length === 1) {
                $listButton.bind('click', function() {
                    var modal = new Modal();
                    modal.load({url: $listButton.data('path')});

                    $(modal).on('ekyna.modal.content', function (e) {
                        if (e.contentType === 'table') {
                            Table.create(e.content, {
                                ajax: true,
                                onSelection: function(elements) {
                                    if ($select.prop('multiple')) {
                                        $select.find('option').prop('selected', false);
                                    }
                                    $(elements).each(function(index, element) {
                                        var $option = $select.find('option[value=' + element.id + ']');
                                        if ($option.length === 1) {
                                            $option.prop('selected', true);
                                        } else {
                                            $option = $('<option />');
                                            $option.prop('value', element.id);
                                            $option.prop('selected', true);
                                            if (element.choice_label !== undefined) {
                                                $option.html(element.choice_label);
                                            } else if (element.name !== undefined) {
                                                $option.html(element.name);
                                            } else if (element.title !== undefined) {
                                                $option.html(element.title);
                                            } else {
                                                $option.html('Entity #' + element.id);
                                            }
                                            $select.append($option);
                                        }
                                    });
                                    // TODO Needed ? More options
                                    /*$select.select2({
                                        selectOnClose: true // For tests ...
                                    });*/
                                    $select.trigger('change');
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
