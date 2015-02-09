;(function(doc, $, router) {
	
	/**
	 * File widget
	 */
	$.fn.filePicker = function(params) {
		
		params = $.extend({
            onChange: null
        }, params);
		
		this.each(function() {

			var $file = $(this).find('input:file');
			var $text = $(this).find('input:text');
			var $button = $(this).find('button');

			$button.unbind('click').bind('click', function(e) {
				e.preventDefault(); $file.trigger('click');
			});

			$text.unbind('click').bind('click', function(e) {
				e.preventDefault(); $file.trigger('click');
			});

			$file.unbind('change').bind('change', function(e) {
				$text.val($file.val().fileName());
                if (typeof params.onChange === 'function') {
                    params.onChange(this);
                }
			});
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

			if($file !== null && $file.length == 1) {
				$rename.updateFromFile = function() {
					if($rename.val().length == 0) {
						$rename.val($file.val().fileName());
					}
					var ext = $file.val().fileName().fileExtension();
					if(ext.length > 0) {
						$rename.stripExtension();
						extension = '.'+ext;
						$rename.normalize();
					}else{
						$rename.getExtension();
					}
				};
				$file.bind('change', $rename.updateFromFile);
				$rename.updateFromFile();
			}else{
				$rename.getExtension();
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
			var $file = $(this).find('.file-picker').filePicker();
			$(this).find('.file-rename').renameWidget({file: $file.find('input:file')});
		});
		return this;
	};

	/**
	 * Image widget
	 */
	$.fn.imageWidget = function(params) {

		params = $.extend({}, params);

		this.each(function() {
			var $file = $(this).find('.file-picker').filePicker({
                onChange: function(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $('[data-preview="' + $(input).attr('id') + '"]')
                                .unbind('click')
                                .bind('click', function(e) {
                                    e.preventDefault();
                                })
                                .find('img').attr('src', e.target.result);
                        };
                        reader.readAsDataURL(input.files[0]);
                    }
                }
            });
			$(this).find('.file-rename').renameWidget({file: $file.find('input:file')});
		});
		return this;
	};

	/**
	 * Collections
	 * @see http://symfony.com/fr/doc/current/cookbook/form/form_collections.html
	 * @see http://symfony.com/fr/doc/current/cookbook/form/create_form_type_extension.html 
	 */
	$.fn.collectionWidget = function(params) {
		
		params = $.extend({}, params);
		
		this.each(function() {
			
			//console.log('collectionWidget');
			
			var $collection = $(this);
			var $container = $collection.find('> .children');
			var prototype = $collection.attr('data-prototype');
			
			$collection.updateChilds = function() {
				var maxIndex = $container.children().length-1;
				$container.find('> div').each(function(index, child) {
					var $controls = $(child).find('.child-controls');
					if($controls.length == 1) {
						$controls.find('button[data-role="move-up"]').prop('disabled', (index == 0));
						$controls.find('button[data-role="move-down"]').prop('disabled', (index == maxIndex));
						$(child).find('input[data-role="position"]').val(index);
					}
				});
			};

			$collection.initChild = function($child) {
				var $imageWidget = $child.find('.image-widget');
				if($imageWidget.length == 1) {
					$imageWidget.imageWidget();
				}
				var $controls = $child.find('.child-controls');
				if($controls.length == 1) {
					$controls.find('button[data-role="remove"]').bind('click', function(e) {
						e.preventDefault();
						if(confirm('Êtes-vous sûr de vouloir supprimer cette élément ?')) {
							$child.remove();
							$collection.updateChilds();
						}
					});
					$controls.find('button[data-role="move-up"]').bind('click', function(e) {
						e.preventDefault();
						$child.prev().before($child.detach());
						$collection.updateChilds();
					});
					$controls.find('button[data-role="move-down"]').bind('click', function(e) {
						e.preventDefault();
						$child.next().after($child.detach());
						$collection.updateChilds();
					});
				}
				$child.formWidget();
			};

			$collection.newChild = function() {
				var $child = $(prototype.replace(/__name__/g, $container.children().length));
				$container.append($child);
				$collection.initChild($child);
				$collection.updateChilds();
			};

			$collection.init = function() {
				var $addChildLink = $('<a href="#" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-plus"></i> Ajouter un élément</a>');
				$collection.append($addChildLink);
				$addChildLink.wrap('<div class="row collection-add"></div>').wrap('<div class="col-md-12"></div>');

				$addChildLink.bind('click', function(e) {
					e.preventDefault();
					$collection.newChild();
				});

				$container.find('> div').each(function(index, child) {
					$collection.initChild($(child));
				});

				$collection.updateChilds();
			};
			
			$collection.init();
		});
		return this;
	};

	$.fn.entityWidget = function(params) {

		params = $.extend({}, params);

		this.each(function() {

			var $entity = $(this);
			var $modal = $('#modal');
			var $addButton = $entity.find('button.new-resource');
			var $listButton = $entity.find('button.list-resource');
			var $select = $entity.find('select');

            $modal
                .off('hidden.bs.modal')
                .on('hidden.bs.modal', function() {
                    $modal.find('.modal-title').html('Modal title');
                    $modal.find('.modal-body').empty();
                });

            if ($addButton.length == 1) {
                $addButton.bind('click', function(e) {
					e.preventDefault();

					var path = $(this).data('path');
					$.ajax({
						url: path,
						dataType: 'xml',
                        cache: false
					})
					.done(function(xmldata) {
                        /* TODO CDATA title */
						var $title = $(xmldata).find('title');
						var $form = $(xmldata).find('form');
						if($title.length == 1) {
							$modal.find('.modal-title').html($title.html());
						}
						if($form.length == 1) {
                            $form = $($form.text());
							$form.find('.form-footer a.form-cancel-btn').click(function(e) {
								e.preventDefault();
								$modal.modal('hide');
							});

							$form.ajaxForm({
								dataType: 'json',
								success: function(data) {
									var $option = $('<option />');
									$option.prop('value', data.id);
									$option.prop('selected', true);
									if(data.name != undefined) {
										$option.html(data.name);
									}else if(data.title != undefined) {
										$option.html(data.title);
									}else{
										$option.html('Entity #' + data.id);
									}
									$select.append($option).select2();
									$modal.modal('hide');
								}
				            });

							$modal
								.off('shown.bs.modal')
								.on('shown.bs.modal', function() {
									$form.formWidget();
									initTinyMCE();
								});

							$modal.find('.modal-body').html($form);
							$modal.modal({show:true});
						}
					});
				});
			}

            if($listButton.length == 1) {
                $listButton.bind('click', function(e) {
                    e.preventDefault();

                    var path = $(this).data('path');
                    $.ajax({
                        url: path,
                        dataType: 'xml',
                        cache: false
                    })
                    .done(function(xmldata) {
                        /* TODO CDATA title */
                        var $title = $(xmldata).find('title');
                        var $list = $(xmldata).find('list');
                        if($title.length == 1) {
                            $modal.find('.modal-title').html($title.html());
                        }
                        if($list.length == 1) {
                            $list = $($list.text());
                            $modal
                                .off('shown.bs.modal')
                                .on('shown.bs.modal', function() {
                                    $list.ekynaTable({
										ajax: true,
                                        onSelection: function(elements) {
                                            if ($select.prop('multiple')) {
                                                $select.find('option').prop('selected', false);
                                            }
                                            $(elements).each(function(index, element) {
                                                var $option = $select.find('option[value=' + element.id + ']');
                                                if ($option.length == 1) {
                                                    console.log('Selecting option #' + element.id);
                                                    $option.prop('selected', true);
                                                } else {
                                                    console.log('Adding option #' + element.id);
                                                    $option = $('<option />');
                                                    $option.prop('value', element.id);
                                                    $option.prop('selected', true);
                                                    if(element.name != undefined) {
                                                        $option.html(element.name);
                                                    }else if(element.title != undefined) {
                                                        $option.html(element.title);
                                                    }else{
                                                        $option.html('Entity #' + element.id);
                                                    }
                                                    $select.append($option);
                                                }
                                                $select.select2();
                                            });
                                            $modal.modal('hide');
                                        }
                                    });
                                });

                            $modal.find('.modal-body').html($list);
                            $modal.modal({show:true});
                        }
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
			var xhr = $.get(router.generate(this.config.route, {'id': parentId}));
			xhr.done(function(results) {
				if ($(results).length == 0) {
					return;
				}
				$(results).each(function(index, result) {
					var $option = $('<option />');
					$option.attr('value', result.value).text(result.text);
					$select.append($option);
				});
				$select.prop('disabled', false);
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
			$(this).find('select').each(function () {
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
			$(this).find('.form-datetime').each(function() {
				$(this).datetimepicker($(this).data('options'));
			});

			/* Textarea autosize */
			$(this).find('textarea').not('.tinymce').autosize({append: "\n"});

			/* File widget */
			$(this).find('.file-widget').fileWidget();

			/* Image widget */
			$(this).find('.image-widget').imageWidget();

			/* Collections */
			$(this).find('.collection-container').collectionWidget();

			/* Entities */
			$(this).find('.entity-widget').entityWidget();
			$(this).find('.entity-search').entitySearchWidget();

			/* Parent choice */
			$(this).find('select[data-parent-choice]').formChoiceParentSelectorWidget();
		});
		return this;
	};

	$(doc).ready(function() {

		$('.form-body').formWidget();

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
		 * this workaround makes magic happen
		 * thanks @harry: http://stackoverflow.com/questions/18111582/tinymce-4-links-plugin-modal-in-not-editable
		 * @see http://jsfiddle.net/e99xf/13/
		 */
		$(doc).on('focusin', function(e) {
		    if ($(e.target).closest(".mce-window").length) {
		        e.stopImmediatePropagation();
		    }
		});
	});

})(document, jQuery, Routing);