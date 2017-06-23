define(['jquery', 'ekyna-form'], function($, Form) {
    "use strict";

    var addField = '[data-collection-role="add"]',
        removeField = '[data-collection-role="remove"]',
        moveUpField = '[data-collection-role="move-up"]',
        moveDownField = '[data-collection-role="move-down"]',
        CollectionAdd = function (el) {
            $(el).on('click', addField, this.addField);
        },
        CollectionRemove = function (el) {
            $(el).on('click', removeField, this.removeField);
        },
        CollectionMoveUp = function (el) {
            $(el).on('click', moveUpField, this.moveUpField);
        },
        CollectionMoveDown = function (el) {
            $(el).on('click', moveDownField, this.moveDownField);
        };

    /*function initializeIndex($collection) {
        var count = 0,
            $list = $collection.find('.ekyna-collection-child-container').first().find('> .ekyna-collection-child'),
            prototypeName = $collection.attr('data-prototype-name'),
            widget = $('#' + $collection.attr('data-prototype')).text();

        var re = new RegExp(widget.match(/id="(.*?)"/)[1].replace(prototypeName, '(\\d+)'));
        $list.each(function(i, child) {
            var match = parseInt($(child).attr('id').match(re)[1]);
            console.log(match);
            if (match > count) {
                count = match;
            }
        });

        $collection.data('child-index', count + 1);
    }*/

    function collectionUpdatePositions($collection) {
        var selector = '[data-collection="' + $collection.attr('id') + '"]',
            $list = $collection.find('.ekyna-collection-child-container').first().find('> .ekyna-collection-child'),
            max = $list.size() - 1;

        $list.each(function(index, li) {
            var $li = $(li);
            $li.find(selector+'[data-collection-role="position"]').first().val(index);
            if (index === 0) {
                $li.find(selector+moveUpField).prop('disabled', true);
            } else {
                $li.find(selector+moveUpField).prop('disabled', false);
            }
            if (index === max) {
                $li.find(selector+moveDownField).prop('disabled', true);
            } else {
                $li.find(selector+moveDownField).prop('disabled', false);
            }
        });
    }

    CollectionAdd.prototype.addField = function (e) {
        e && e.preventDefault();

        var $this = $(this),
            selector = $this.attr('data-collection'),
            $collection = $('#'+selector),
            $list = $collection.find('.ekyna-collection-child-container').first(),
            prototypeName = $this.attr('data-prototype-name'),
            widget = $('#' + $collection.attr('data-prototype')).text(),
            index = $collection.data('child-index');

        // If child index is not available as a collection data
        if (index === undefined) {
            index = -1;

            // Determine the next index
            var indexRegex = new RegExp(widget.match(/id="(.*?)"/)[1].replace(prototypeName, '(\\d+)'));

            $list.find('> .ekyna-collection-child').each(function(i, child) {
                var match = parseInt($(child).attr('id').match(indexRegex)[1]);
                if (match > index) {
                    index = match;
                }
            });
        }

        // Stores the current child index
        index++;
        $collection.data('child-index', index);

        // Builds the child widget
        var name = widget.match(/id="(.*?)"/),
            widgetRegex = new RegExp(prototypeName, "g");

        widget = widget.replace(widgetRegex, index);
        widget = widget.replace(/__id__/g, name[1].replace(widgetRegex, index));
        var $element = $(widget);
        $list.append($element);

        // Initialize the child form
        var form = Form.create($element);
        form.init();

        collectionUpdatePositions($collection);

        var event = $.Event('ekyna-collection-field-added');
        event.target = $element;
        $collection.trigger(event);
    };

    CollectionRemove.prototype.removeField = function (e) {
        var $this = $(this),
            selector = $this.attr('data-collection');

        e && e.preventDefault();

        if ($this.data('confirm')) {
            if (!confirm($this.data('confirm'))) {
                return;
            }
        }

        var $element = $this.closest('.ekyna-collection-child');

        var form = Form.create($element);
        form.save();
        form.destroy();

        $element.remove();

        var $collection = $('#'+selector);
        collectionUpdatePositions($collection);

        var event = $.Event('ekyna-collection-field-removed');
        event.target = $element;
        $collection.trigger(event);
    };

    CollectionMoveUp.prototype.moveUpField = function (e) {
        var $this = $(this),
            selector = $this.attr('data-collection');

        e && e.preventDefault();

        var $element = $this.closest('.ekyna-collection-child');
        if (!$element.is(':first-child')) {
            var $prev = $element.prev();

            var form = Form.create($element);
            form.save();
            form.destroy();

            var prevForm = Form.create($prev);
            prevForm.save();
            prevForm.destroy();

            $prev.before($element.detach());

            form.init($element);
            prevForm.init($prev);

            var $collection = $('#'+selector);
            collectionUpdatePositions($collection);

            var event = $.Event('ekyna-collection-field-moved-up');
            event.target = $element;
            $collection.trigger(event);
        }
    };

    CollectionMoveDown.prototype.moveDownField = function (e) {
        var $this = $(this),
            selector = $this.attr('data-collection');

        e && e.preventDefault();

        $this.trigger('ekyna-collection-field-moved-down');
        var $element = $this.closest('.ekyna-collection-child');
        if (!$element.is(':last-child')) {
            var $next = $element.next();

            var form = Form.create($element);
            form.save();
            form.destroy();

            var nextForm = Form.create($next);
            nextForm.save();
            nextForm.destroy();

            $next.after($element.detach());

            form.init($element);
            nextForm.init($next);

            var $collection = $('#'+selector);
            collectionUpdatePositions($collection);

            var event = $.Event('ekyna-collection-field-moved-down');
            event.target = $element;
            $collection.trigger(event);
        }
    };

    var oldAdd = $.fn.addField;
    var oldRemove = $.fn.removeField;
    var oldMoveUp = $.fn.moveUpField;
    var oldMoveDown = $.fn.moveDownField;

    $.fn.addField = function (option) {
        return this.each(function () {
            var $this = $(this),
                data = $this.data('addfield')
                ;
            if (!data) {
                $this.data('addfield', (data = new CollectionAdd(this)));
            }
            if (typeof option === 'string') {
                data[option].call($this);
            }
        });
    };

    $.fn.removeField = function (option) {
        return this.each(function() {
            var $this = $(this),
                data = $this.data('removefield')
                ;
            if (!data) {
                $this.data('removefield', (data = new CollectionRemove(this)));
            }
            if (typeof option === 'string') {
                data[option].call($this);
            }
        });
    };

    $.fn.moveUpField = function (option) {
        return this.each(function() {
            var $this = $(this),
                data = $this.data('moveupfield')
                ;
            if (!data) {
                $this.data('moveupfield', (data = new CollectionMoveUp(this)));
            }
            if (typeof option === 'string') {
                data[option].call($this);
            }
        });
    };

    $.fn.moveDownField = function (option) {
        return this.each(function() {
            var $this = $(this),
                data = $this.data('movedownfield')
                ;
            if (!data) {
                $this.data('movedownfield', (data = new CollectionMoveDown(this)));
            }
            if (typeof option === 'string') {
                data[option].call($this);
            }
        });
    };

    $.fn.addField.Constructor = CollectionAdd;
    $.fn.removeField.Constructor = CollectionRemove;
    $.fn.moveUpField.Constructor = CollectionMoveUp;
    $.fn.moveDownField.Constructor = CollectionMoveDown;

    $.fn.addField.noConflict = function () {
        $.fn.addField = oldAdd;
        return this;
    };
    $.fn.removeField.noConflict = function () {
        $.fn.removeField = oldRemove;
        return this;
    };
    $.fn.moveUpField.noConflict = function () {
        $.fn.moveUpField = oldMoveUp;
        return this;
    };
    $.fn.moveDownField.noConflict = function () {
        $.fn.moveDownField = oldMoveDown;
        return this;
    };

    $(document).on('click.addfield.data-api', addField, CollectionAdd.prototype.addField);
    $(document).on('click.removefield.data-api', removeField, CollectionRemove.prototype.removeField);
    $(document).on('click.moveupfield.data-api', moveUpField, CollectionMoveUp.prototype.moveUpField);
    $(document).on('click.movedownfield.data-api', moveDownField, CollectionMoveDown.prototype.moveDownField);

    return {
        init: function($element) {
            $element.each(function(index, collection) {
                collectionUpdatePositions($(collection));
                $(collection).attr('data-initialized', 1);
            });
        }
    };
});
