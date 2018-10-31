define(['require', 'jquery', 'bootstrap/dialog'], function(require, $, BootstrapDialog) {
    "use strict";

    var triggerSelector = 'button[data-modal], a[data-modal], [data-modal] > a';

    var EkynaModal = function() {
        this.dialog = new BootstrapDialog();
        this.form = null;
        this.shown = false;

        var that = this,
            $that = $(this);

        // Handle shown dialog
        this.dialog.onShow(function () {
            var event = $.Event('ekyna.modal.show');
            event.modal = that;
            $that.trigger(event);

            // Auto modal buttons and links
            that.dialog
                .getModalBody()
                .on('click', triggerSelector, function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    that.load({url: $(e.currentTarget).attr('href')});

                    return false;
                });
        });

        // Handle shown dialog
        this.dialog.onShown(function () {
            that.shown = true;

            var event = $.Event('ekyna.modal.shown');
            event.modal = that;
            $that.trigger(event);
        });

        // Handle hide dialog
        this.dialog.onHide(function () {
            that.shown = false;

            var event = $.Event('ekyna.modal.hide');
            event.modal = that;
            $that.trigger(event);
            if (event.isDefaultPrevented()) {
                return false;
            }

            if (that.form) {
                that.form.destroy();
                that.form = null;
            }
        });

        // Handle hide dialog
        this.dialog.onHidden(function () {
            var event = $.Event('ekyna.modal.hidden');
            event.modal = that;
            $that.trigger(event);
        });
    };

    EkynaModal.prototype = {
        constructor: EkynaModal,
        load: function(params) {
            params.cache = false;

            var that = this,
                xhr = $.ajax(params);

            xhr.done(function(data, status, jqXHR) {
                that.handleResponse(data, status, jqXHR);
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
            $(this.dialog.getModal()).removeAttr('tabindex');

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
                            success: function (data, status, jqXHR) {
                                that.handleResponse(data, status, jqXHR);
                            }
                        });
                    }, 100);

                    return false;
                });
            });
        },
        getContentType: function(jqXHR) {
            var type = 'html',
                header = jqXHR.getResponseHeader('content-type');

            if (/json/.test(header)) {
                type = 'json';
            } else if (/xml/.test(header)) {
                type = 'xml';
            }

            return type;
        },
        handleResponse: function(data, status, jqXHR) {
            var that = this,
                $that = $(this),
                type = this.getContentType(jqXHR),
                event;

            if (this.form) {
                this.form.destroy();
                this.form = null;
            }

            function redirectOrReload(data) {
                // Load url
                if (data.hasOwnProperty('load_url') && data.load_url) {
                    that.load({
                        url: data.load_url,
                        method: 'GET'
                    });

                    return true;
                }

                // Redirect url
                if (data.hasOwnProperty('redirect_url') && data.redirect_url) {
                    window.location.href = data.redirect_url;

                    return true;
                }

                return false;
            }

            event = $.Event('ekyna.modal.response');
            event.modal = this;
            event.contentType = type;
            event.content = data;
            event.jqXHR = jqXHR;

            $that.trigger(event);

            if (event.isDefaultPrevented()) {
                return this;
            }

            // Redirect or reload
            if (type === 'json' && redirectOrReload(event.content)) {
                return this;
            }

            if (type !== 'xml') {
                return this;
            }

            var $xmlData = $(data);

            // Content
            var $content = $xmlData.find('content');
            if ($content.length > 0) {
                type = $content.attr('type');
                event = $.Event('ekyna.modal.content');
                event.modal = this;
                event.contentType = type;

                var content = $content.text();

                // Data content type
                if (type === 'data') {
                    event.content = JSON.parse(content);
                    $that.trigger(event);

                    if (event.isDefaultPrevented()) {
                        return this;
                    }

                    // Redirect or reload
                    if (redirectOrReload(event.content)) {
                        return this;
                    }

                    // (default) Close modal
                    return this.close();
                }

                // Html content type
                var $html = $(content);
                event.content = $html;
                $that.trigger(event);
                if (event.isDefaultPrevented()) {
                    return this.close();
                }

                this.dialog.setMessage($html);

                // Form content type
                if (type === 'form') {
                    $html.each(function() {
                        var $form = $(this);
                        if ($form.is('form')) {
                            if (that.shown) {
                                that.initForm($form);
                            } else {
                                $that.one('ekyna.modal.shown', function() {
                                    that.initForm($form);
                                });
                            }
                        }
                    });
                }

                // Updated content event
                $that.trigger($.Event('ekyna.modal.updated'));
            } else {
                // No content => abort
                return this;
            }

            // Title
            var $title = $xmlData.find('title');
            if (1 === $title.length) {
                this.dialog.setTitle($title.text());
            }

            // Buttons
            var $buttons = $xmlData.find('buttons');
            if (1 === $buttons.length) {
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
                                $that.trigger(event);

                                if (that.form && button.id === 'submit' && !event.isDefaultPrevented()) {
                                    that.form.getElement().submit();
                                }
                            };
                        }
                    }
                });
                this.dialog.setButtons(buttons);
            } else {
                this.dialog.setButtons([]);
            }

            // Type and Size
            var config = JSON.parse($xmlData.find('config').text());
            if (typeof config.type !== 'undefined') {
                this.dialog.setType(config.type);
            }
            if (typeof config.size !== 'undefined') {
                this.dialog.setSize(config.size);
            }
            if (typeof config.cssClass !== 'undefined') {
                this.dialog.setCssClass(config.cssClass);
            }

            // Handle open/shown dialog
            if (!this.dialog.isOpened()) {
                this.dialog.open();
            }

            if (typeof config.condensed !== 'undefined') {
                if (config.condensed) {
                    this.dialog.getModal().addClass('condensed');
                } else {
                    this.dialog.getModal().removeClass('condensed');
                }
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
    $(document).on('click', triggerSelector, function(e) {
        e.preventDefault();

        var modal = new EkynaModal();
        modal.load({url: $(e.currentTarget).attr('href')});

        return false;
    });

    return EkynaModal;

});
