$(function() {

	/**
	 * File widget
	 */
	$.fn.filePicker = function(params) {

		params = $.extend({
            onChange: null,
            onClear: null
        }, params);

		this.each(function() {

			var $file = $(this).find('input:file');
			var $text = $(this).find('input:text');
            var current = $text.data('current') || null;
			var $pickButton = $(this).find('button[data-role="pick"]');
			var $clearButton = $(this).find('button[data-role="clear"]');

            var $key = $('input[data-target="' + $file.attr('id') + '"]');
            var $form = $file.closest('form');
            var uploadXhr = null;
            var $progressBar = $('div#' + $file.attr('id') + '_progress');

            $pickButton.unbind('click').bind('click', function(e) {
				e.preventDefault();
                $file.trigger('click');
			});

            $clearButton.unbind('click').bind('click', function(e) {
				e.preventDefault();
                if ($file.files) {
                    $file.files = [];
                }
                if ($key.length == 1) {
                    $key.val(null);
                }
                if (uploadXhr) {
                    uploadXhr.abort();
                }
                $file.val(null).trigger('change');
                if (typeof params.onClear === 'function') {
                    params.onClear($file);
                }
            }).trigger('click');

			$text.unbind('click').bind('click', function(e) {
				e.preventDefault();
                $file.trigger('click');
			});

			$file.unbind('change').bind('change', function(e) {
                if (uploadXhr) {
                    uploadXhr.abort();
                }
                var val = $file.val();
                if (0 < val.length) {
                    $text.val(val.fileName());
                } else {
                    $text.val(current);
                }
                if (typeof params.onChange === 'function') {
                    params.onChange(this);
                }
			});

            if ($key.length == 1) {
                $file
                    .fileupload()
                    .bind('fileuploadadd', function (e, data) {
                        uploadXhr = data.submit();
                    })
                    .bind('fileuploadsubmit', function (e, data) {
                        var count = $form.data('uploadCount') || 0;
                        count++;
                        $form.find('[type=submit]').prop('disabled', true);
                        $form.data('uploadCount', count);
                        $progressBar.fadeIn();
                    })
                    .bind('fileuploadalways', function (e, data) {
                        var count = $form.data('uploadCount') || 0;
                        count--;
                        $form.data('uploadCount', count);
                        if (0 >= count) {
                            $form.find('[type=submit]').prop('disabled', false);
                        }
                        $progressBar.fadeOut()
                            .find('.progress-bar')
                            .css({width: '0%'})
                            .attr('aria-valuenow', 0);
                        uploadXhr = null;
                    })
                    .bind('fileuploaddone', function (e, data) {
                        var result = JSON.parse(data.result);
                        if (result.hasOwnProperty('upload_key')) {
                            $key.val(result.upload_key);
                        }
                    })
                    .bind('fileuploadprogress', function (e, data) {
                        if (data._progress) {
                            var progress = parseInt(data._progress.loaded / data._progress.total * 100, 10);
                            $progressBar
                                .find('.progress-bar')
                                .css({width: progress + '%'})
                                .attr('aria-valuenow', progress);
                        }
                    })
                ;

                $form.bind('submit', function(e) {
                    var count = $form.data('uploadCount') || 0;
                    if (0 < count) {
                        $form.find('[type=submit]').qtip({
                            content: 'Veuillez patienter pendant le téléchargement de vos fichiers.',
                            style: { classes: 'qtip-bootstrap' },
                            hide: { fixed: true, delay: 300 },
                            position: {
                                my: 'bottom center',
                                at: 'top center',
                                target: 'mouse',
                                adjust: {
                                    mouse: false,
                                    scroll: false
                                }
                            }
                        });
                        e.preventDefault();
                        return false;
                    }
                });
            }
		});
		return this;
	};

	/**
	 * Rename widget
	 */
	$.fn.renameWidget = function(params) {

		params = $.extend({file: null}, params);

		this.each(function() {

			var $rename = $(this);
			var $file = params.file;
			var extension = '';
			var defaultValue = $rename.val();

			$rename.stripExtension = function() {
				if(extension.length == 0) return;
				var extension_index = $rename.val().lastIndexOf(extension);
				if(extension_index > 0) {
					$rename.val($rename.val().substring(0, extension_index));
				}
			};

			$rename.appendExtension = function() {
				$rename.val($rename.val() + extension);
			};

			$rename.normalize = function() {
				$rename.stripExtension();
				var value = $rename.val().trim().urlize();
				if(value.length > 0) {
					$rename.val(value);
					$rename.appendExtension();
				}else{
					$rename.val(defaultValue);
				}
			};

			$rename.getExtension = function() {
				var ext = $rename.val().fileExtension();
				if(ext.length > 0) {
					extension = '.'+ext;
				}
				$rename.normalize();
			};
            $rename.getExtension();

			if ($file !== null && $file.length == 1) {
				$rename.updateFromFile = function() {
                    var fileVal = $file.val();
                    if (0 < fileVal.length) {
                        if ($rename.val().length == 0) {
                            $rename.val(fileVal.fileName());
                        }
                        var ext = fileVal.fileName().fileExtension();
                        if (ext.length > 0) {
                            $rename.stripExtension();
                            extension = '.'+ext;
                            $rename.normalize();
                        } else {
                            $rename.getExtension();
                        }
                    } else {
                        $rename.val(defaultValue);
                        $rename.getExtension();
                    }
				};
				$file.bind('change', $rename.updateFromFile);
				$rename.updateFromFile();
			}

			$rename.bind('focus', function() {
				$rename.stripExtension();
			});

			$rename.bind('blur', $rename.normalize);
		});
		return this;
	};

	/**
	 * File widget
	 */
	$.fn.fileWidget = function(params) {

		params = $.extend({}, params);

		this.each(function() {
			var $file = $(this).find('.file-picker').find('input:file');
			$(this).find('.file-rename').renameWidget({file: $file});
		});
		return this;
	};

	/**
	 * Image widget
	 */
	$.fn.imageWidget = function(params) {

		params = $.extend({}, params);

		this.each(function() {
            var $picker = $(this).find('.file-picker');
            var $file = $picker.find('input:file');
            var $preview = $('[data-preview="' + $file.attr('id') + '"]');
            var current = $preview.find('img').attr('src');
            $picker.filePicker({
                onChange: function(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $preview
                                .unbind('click')
                                .bind('click', function(e) {
                                    e.preventDefault();
                                })
                                .find('img').attr('src', e.target.result);
                        };
                        reader.readAsDataURL(input.files[0]);
                    } else {
                        $preview.find('img').attr('src', current);
                    }
                }
            });
			$(this).find('.file-rename').renameWidget({file: $file});
		});
		return this;
	};

	$.fn.entityWidget = function(params) {

		params = $.extend({}, params);

		this.each(function() {

			var $entity = $(this);
			var $addButton = $entity.find('button.new-resource');
			var $listButton = $entity.find('button.list-resource');
			var $select = $entity.find('select');

            if ($addButton.length == 1) {
                $addButton.bind('click', function(e) {
                    requirejs(['ekyna-modal'], function (EkynaModal) {

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
				});
			}

            if ($listButton.length == 1) {
                $listButton.bind('click', function(e) {
                    requirejs(['ekyna-modal', 'ekyna-table'], function (EkynaModal) {

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
                });
            }
		});
		return this;
	};

	/**
	 * Entity search widget
	 */
	$.fn.entitySearchWidget = function(params) {

		params = $.extend({
			limit: 8
		}, params);

		this.each(function() {

			var $this = $(this);

			var searchUrl = Routing.generate($this.data('search'));
			var findUrl = Routing.generate($this.data('find'));
			var allowClear = $this.data('clear') == 1;

			$this.select2({
			    placeholder: 'Rechercher ...',
			    minimumInputLength: 0,
			    allowClear: allowClear,
			    ajax: {
			        quietMillis: 300,
			        url: searchUrl,
			        dataType: 'jsonp',
			        data: function (term, page) {
			            return {
			                limit: params.limit,
			                search: term
			            };
			        },
			        results: function (data, page) {
			            return { results: data.results };
			        }
			    },
			    initSelection : function (element, callback) {
			    	var id = parseInt(element.val());
			    	if(id > 0) {
			    		$.ajax({
			    			url: findUrl,
			    			data: {id: id},
			    			dataType: 'json'
			    		})
			    		.done(function(data) {
			    			callback(data);
			    		});
			    	}
			    }
			});
		});
		return this;
	};

	/**
	 * Choice parent selector
	 */
	var FormChoiceParentSelector = function(elem, options){
		this.elem = elem;
		this.$elem = $(elem);
		this.$parent = null;
		this.options = options;
		this.metadata = this.$elem.data('parent-choice');
	};

	FormChoiceParentSelector.prototype = {
		defaults: {},
		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.metadata);
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
			var xhr = $.get(Routing.generate(this.config.route, {'id': parentId}));
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

	window.FormChoiceParentSelector = FormChoiceParentSelector;


	/**
	 *  Form widget
	 */
	$.fn.formWidget = function(params) {

		params = $.extend({}, params);

		this.each(function() {

			/* Selects to select2 */
			$(this).find('select').not('.no-select2').each(function () {
				var allowclear = $(this).data('allow-clear') == 1;
				$(this).select2({
					allowClear: allowclear
				});
			});

			/* Color picker picker */
			$(this).find('.form-color-picker').each(function() {
				$(this).find('input[type="text"]').ColorPickerSliders($(this).data('options'));
			});

			/* Datetime picker */
			$(this).find('.form-datetime-picker').each(function() {
				$(this).datetimepicker($(this).data('options'));
			});

			/* Textarea autosize */
			$(this).find('textarea').not('.tinymce').autosize({append: "\n"});

            /* File pickers */
            $(this).find('.file-picker').filePicker();

			/* File widget */
			$(this).find('.file-widget').fileWidget();

			/* Image widget */
			$(this).find('.image-widget').imageWidget();

			/* Entities */
			$(this).find('.entity-widget').entityWidget();
			$(this).find('.entity-search').entitySearchWidget();

			/* Parent choice */
			$(this).find('select[data-parent-choice]').formChoiceParentSelectorWidget();
		});
		return this;
	};

    /**
     * Form with tabs error handler
     * @see http://jsfiddle.net/GJeez/8/
     * @see http://www.html5rocks.com/en/tutorials/forms/constraintvalidation/?redirect_from_locale=fr#toc-checkValidity
     */
    $(".form-with-tabs input, .form-with-tabs textarea, .form-with-tabs select").on('invalid', function(event) {
        var $tab = $(event.target).parents('.tab-pane').eq(0);
        if ($tab.length == 1) {
            var $a = $('a[href="#' + $tab.attr('id') + '"]');
            if ($a.length == 1) {
                $a.tab('show');
                return;
            }
        }
        event.preventDefault();
    });

    /**
     * Tinymce modal fix
     * thanks @harry: http://stackoverflow.com/questions/18111582/tinymce-4-links-plugin-modal-in-not-editable
     * @see http://jsfiddle.net/e99xf/13/
     */
    $(document).on('focusin', function(e) {
        if ($(e.target).closest(".mce-window").length) {
            e.stopImmediatePropagation();
        }
    });

    $('.form-body').formWidget();

});