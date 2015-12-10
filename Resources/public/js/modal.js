define('ekyna-modal', ['require', 'jquery', 'bootstrap-dialog'], function(require, $, bsDialog) {
    "use strict";

    var EkynaModal = function() {
        this.dialog = new bsDialog();
        this.form = null;
    };

    EkynaModal.prototype = {
        constructor: EkynaModal,
        load: function(params) {
            params.dataType = 'xml';
            params.cache = false;

            var that = this;
            var xhr = $.ajax(params);
            xhr.done(function(xmlData) {
                that.handleResponse(xmlData);
            });
            xhr.fail(function() {
                console.log('Failed to load modal.');
                var event = $.Event('ekyna.modal.load_fail');
                $(that).trigger(event);
            });
        },
        handleResponse: function(xmlData) {
            var that = this;
            var $xmlData = $(xmlData);
            var event = null;

            if (that.form) {
                that.form.destroy();
                that.form = null;
            }

            // Content
            var $content = $xmlData.find('content');
            if ($content.size() > 0) {
                var type = $content.attr('type');
                event = $.Event('ekyna.modal.content');
                event.modal = that;
                event.contentType = type;

                var content = $content.text();

                // Data content type
                if (type === 'data') {
                    event.content = JSON.parse(content);
                    $(that).trigger(event);
                    if (that.dialog.isOpened()) {
                        that.dialog.close();
                    }
                    return;
                }

                // Html content type
                var $html = $(content);
                event.content = $html;
                $(that).trigger(event);
                if (event.isDefaultPrevented()) {
                    if (that.dialog.isOpened()) {
                        that.dialog.close();
                    }
                    return;
                }

                that.dialog.setMessage($html);

                // Form content type
                if (type === 'form') {
                    require(['ekyna-form'], function (Form) {
                        that.form = Form.create($html);
                        that.form.init();

                        that.form.getElement().on('submit', function(e) {
                            e.preventDefault();

                            that.dialog.enableButtons(false);
                            var submitButton = that.dialog.getButton('submit');
                            if (submitButton) {
                                submitButton.spin();
                            }

                            that.form.save();
                            setTimeout(function () {
                                that.form.getElement().ajaxSubmit({
                                    dataType: 'xml',
                                    success: function (response) {
                                        that.handleResponse(response);
                                    }
                                });
                            }, 100);

                            return false;
                        });
                    });
                }
            } else {
                // No content => abort
                return;
            }

            // Title
            var $title = $xmlData.find('title');
            if ($title.size() > 0) {
                that.dialog.setTitle($title.text());
            }

            // Type and Size
            var config = JSON.parse($xmlData.find('config').text());
            if (config.type) {
                that.dialog.setType(config.type);
            }
            if (config.size) {
                that.dialog.setSize(config.size);
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
                    if (typeof button.action != "function") {
                        if (button.id == 'close') {
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

                                if (that.form && button.id == 'submit' && !event.isDefaultPrevented()) {
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

            // Handle hide dialog
            that.dialog.onHide(function () {
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

            // Handle open/shown dialog
            if (!that.dialog.isOpened()) {
                that.dialog.open();
            }
        },
        getDialog: function() {
            return this.dialog;
        }
    };

    // Auto modal buttons and links
    $('body').on('click', 'button[data-modal="true"], a[data-modal="true"], [data-modal="true"] > a', function(e) {
        e.preventDefault();

        var modal = new EkynaModal();
        modal.load({url: $(this).attr('href')});

        return false;
    });

    return EkynaModal;

});
