(function(root, factory) {
    "use strict";

    // CommonJS module is defined
    if (typeof module !== 'undefined' && module.exports) {
        module.exports = factory(require('jquery'), require('bootstrap-dialog'));
    }
    // AMD module is defined
    else if (typeof define === 'function' && define.amd) {
        define('ekyna-modal', ['jquery', 'bootstrap-dialog'], function($, bsDialog) {
            return factory($, bsDialog);
        });
    } else {
        // planted over the root!
        root.EkynaModal = factory(root.jQuery, root.BootstrapDialog);
    }

}(this, function($, bsDialog) {
    "use strict";

    var EkynaModal = function() {
        this.dialog = new bsDialog();
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

            var $content = $xmlData.find('content');
            if ($content.size() > 0) {
                var type = $content.attr('type');
                event = $.Event('ekyna.modal.content');
                event.contentType = type;
                var content = $content.text();
                if (type === 'data') {
                    event.content = JSON.parse(content);
                } else {
                    var $html = $(content); // html, form or table
                    event.content = $html;
                    that.dialog.setMessage($html);
                }
                // Prevent dialog open
                if (type === 'data' || event.isDefaultPrevented()) {
                    $(that).trigger(event);
                    return;
                }
            }

            var $title = $xmlData.find('title');
            if ($title.size() > 0) {
                that.dialog.setTitle($title.text());
            }

            var config = JSON.parse($xmlData.find('config').text());
            if (config.type) {
                that.dialog.setType(config.type);
            }
            if (config.size) {
                that.dialog.setSize(config.size);
            }

            var $buttons = $xmlData.find('buttons');
            if ($buttons.size() > 0) {
                var buttons = JSON.parse($buttons.text());
                $(buttons).each(function(index, button) {
                    if (button.id == 'close') {
                        button.action = function (dialog) {
                            dialog.enableButtons(false);
                            dialog.close();
                        };
                    } else {
                        button.action = function (dialog) {
                            dialog.enableButtons(false);
                            var event = $.Event('ekyna.modal.button_click');
                            event.buttonId = button.id;
                            $(that).trigger(event);
                        };
                    }
                });
                that.dialog.setButtons(buttons);
            } else {
                that.dialog.setButtons([]);
            }

            if (that.dialog.isOpened()) {
                if (event) {
                    $(that).trigger(event);
                }
            } else {
                that.dialog.onShown(function() {
                    if (event) {
                        $(that).trigger(event);
                    }
                });
                that.dialog.open();
            }
        },
        getDialog: function() {
            return this.dialog;
        }
    };

    return EkynaModal;

}));
