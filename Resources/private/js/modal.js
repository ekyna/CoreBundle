/**
 * TODO move to https://github.com/makeusabrew/bootbox ?
 */
define(['require', 'jquery', 'bootstrap/dialog'], function(require, $, BootstrapDialog) {
    "use strict";

    function contentType(jqXHR) {
        var type = 'html',
            header = jqXHR.getResponseHeader('content-type');

        if (/json/.test(header)) {
            type = 'json';
        } else if (/xml/.test(header)) {
            type = 'xml';
        }

        return type;
    }

    var EkynaModal = function() {
        this.dialog = new BootstrapDialog();
        this.form = null;
        this.shown = false;


        var that = this;
        // Handle shown dialog
        that.dialog.onShow(function () {
            var event = $.Event('ekyna.modal.show');
            event.modal = that;
            $(that).trigger(event);

            // Auto modal buttons and links
            that.dialog
                .getModalBody()
                .on('click', 'button[data-modal], a[data-modal], [data-modal] > a', function(e) {
                    e.preventDefault();

                    that.load({url: $(e.currentTarget).attr('href')});

                    return false;
                });
        });

        // Handle shown dialog
        that.dialog.onShown(function () {
            that.shown = true;

            var event = $.Event('ekyna.modal.shown');
            event.modal = that;
            $(that).trigger(event);
        });

        // Handle hide dialog
        that.dialog.onHide(function () {
            that.shown = false;

            var event = $.Event('ekyna.modal.hide');
            event.modal = that;
            $(that).trigger(event);
            if (event.isDefaultPrevented()) {
                return false;
            }

            if (that.form) {
                that.form.destroy();
                that.form = null;
            }
        });

        // Handle hide dialog
        that.dialog.onHidden(function () {
            var event = $.Event('ekyna.modal.hidden');
            event.modal = that;
            $(that).trigger(event);
        });
    };

    EkynaModal.prototype = {
        constructor: EkynaModal,
        load: function(params) {
            params.cache = false;

            var that = this,
                xhr = $.ajax(params);

            xhr.done(function(data, textStatus, jqXHR) {
                that.handleResponse(data, textStatus, jqXHR);
            });

            xhr.fail(function() {
                console.log('Failed to load modal.');
                var event = $.Event('ekyna.modal.load_fail');
                $(that).trigger(event);
            });

            return xhr;
        },
        initForm: function($form) {
            var that = this;

            // @see https://github.com/select2/select2/issues/600
            $(that.dialog.getModal()).removeAttr('tabindex');

            require(['ekyna-form'], function (Form) {
                that.form = Form.create($form);
                that.form.init(that.dialog.getModal());

                that.form.getElement().on('submit', function (e) {
                    e.preventDefault();

                    that.dialog.enableButtons(false);
                    var submitButton = that.dialog.getButton('submit');
                    if (submitButton) {
                        submitButton.spin();
                    }

                    that.form.save();
                    setTimeout(function () {
                        that.form.getElement().ajaxSubmit({
                            success: function (data, textStatus, jqXHR) {
                                that.handleResponse(data, textStatus, jqXHR);
                            }
                        });
                    }, 100);

                    return false;
                });
            });
        },
        handleResponse: function(data, textStatus, jqXHR) {
            var that = this, type = contentType(jqXHR), event;

            if (that.form) {
                that.form.destroy();
                that.form = null;
            }

            event = $.Event('ekyna.modal.response');
            event.modal = that;
            event.contentType = type;
            event.content = data;

            $(that).trigger(event);
            if (event.isDefaultPrevented()) {
                return that;
            }

            if (type !== 'xml') {
                return this;
            }

            var $xmlData = $(data);

            // Content
            var $content = $xmlData.find('content');
            if ($content.size() > 0) {
                type = $content.attr('type');
                event = $.Event('ekyna.modal.content');
                event.modal = that;
                event.contentType = type;

                var content = $content.text();

                // Data content type
                if (type === 'data') {
                    event.content = JSON.parse(content);
                    $(that).trigger(event);
                    return that.close();
                }

                // Html content type
                var $html = $(content);
                event.content = $html;
                $(that).trigger(event);
                if (event.isDefaultPrevented()) {
                    return that.close();
                }

                that.dialog.setMessage($html);

                // Form content type
                if (type === 'form') {
                    $html.each(function() {
                        var $form = $(this);
                        if ($form.is('form')) {
                            if (that.shown) {
                                that.initForm($form);
                            } else {
                                $(that).one('ekyna.modal.shown', function() {
                                    that.initForm($form);
                                });
                            }
                        }
                    });
                }
            } else {
                // No content => abort
                return this;
            }

            // Title
            var $title = $xmlData.find('title');
            if ($title.size() > 0) {
                that.dialog.setTitle($title.text());
            }

            // Type and Size
            var config = JSON.parse($xmlData.find('config').text());
            if (typeof config.type !== 'undefined') {
                that.dialog.setType(config.type);
            }
            if (typeof config.size !== 'undefined') {
                that.dialog.setSize(config.size);
            }
            if (typeof config.cssClass !== 'undefined') {
                that.dialog.setCssClass(config.cssClass);
            }

            // Buttons
            var $buttons = $xmlData.find('buttons');
            if ($buttons.size() > 0) {
                var buttons = JSON.parse($buttons.text(), function (key, value) {
                    if (value && (typeof value === 'string') && value.indexOf("function") === 0) {
                        return new Function('return ' + value)();
                    }
                    return value;
                });
                $(buttons).each(function(index, button) {
                    if (typeof button.action !== "function") {
                        if (button.id === 'close') {
                            button.action = function (dialog) {
                                dialog.enableButtons(false);
                                dialog.close();
                            };
                        } else {
                            button.action = function (dialog) {
                                dialog.enableButtons(false);
                                var event = $.Event('ekyna.modal.button_click');
                                event.modal = that;
                                event.buttonId = button.id;
                                $(that).trigger(event);

                                if (that.form && button.id === 'submit' && !event.isDefaultPrevented()) {
                                    that.form.getElement().submit();
                                }
                            };
                        }
                    }
                });
                that.dialog.setButtons(buttons);
            } else {
                that.dialog.setButtons([]);
            }

            // Handle open/shown dialog
            if (!that.dialog.isOpened()) {
                that.dialog.open();
            }

            return this;
        },
        close: function() {
            if (this.dialog.isOpened()) {
                this.dialog.close();
            }
            return this;
        },
        getDialog: function() {
            return this.dialog;
        }
    };

    // Auto modal buttons and links
    $(document).on('click', 'button[data-modal], a[data-modal], [data-modal] > a', function(e) {
        e.preventDefault();

        var modal = new EkynaModal();
        modal.load({url: $(e.currentTarget).attr('href')});

        return false;
    });

    return EkynaModal;

});
