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
                var event = jQuery.Event('ekyna.modal.load_fail');
                $(that).trigger(event);
            });
        },
        handleResponse: function(xmlData) {
            var that = this;
            var $xmlData = $(xmlData);

            var $content = $xmlData.find('content');
            if ($content.size() > 0) {
                var type = $content.attr('type');
                var event = jQuery.Event('ekyna.modal.content');
                event.contentType = type;
                var content = $content.text();
                if (type === 'data') {
                    event.content = JSON.parse(content);
                } else {
                    var $html = $(content); // html, form or table
                    event.content = $html;
                    that.dialog.setMessage($html);
                }
                $(that).trigger(event);
                // Prevent dialog open
                if (type === 'data' || event.isDefaultPrevented()) {
                    return;
                }
            }

            var $title = $xmlData.find('title');
            if ($title.size() > 0) {
                this.dialog.setTitle($title.text());
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
                            var event = jQuery.Event('ekyna.modal.button_click');
                            event.buttonId = button.id;
                            $(that).trigger(event);
                        };
                    }
                });
                that.dialog.setButtons(buttons);
            } else {
                that.dialog.setButtons([]);
            }

            that.dialog.open();
        },
        getDialog: function() {
            return this.dialog;
        }
    };

    return EkynaModal;

}));
