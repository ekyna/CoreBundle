;(function ($) {
	
	/**
	 * File widget
	 */
	$.fn.fileWidget = function(params) {
		
		params = $.extend({}, params);
		
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

			$file.unbind('change').bind('change', function() {
				$text.val($file.val().fileName());
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
				var value = $rename.val().trim().normalize();
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
	 * Image widget
	 */
	$.fn.imageWidget = function(params) {
		
		params = $.extend({}, params);
		
		this.each(function() {
			var $file = $(this).find('.file-widget').fileWidget();
			$(this).find('.rename-widget').renameWidget({file: $file.find('input:file')});
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
			
			var $collection = $(this);
			var $container = $collection.find('> .childs');
			var prototype = $collection.attr('data-prototype');
			
			$collection.updateChilds = function() {
				var maxIndex = $container.children().length-1;
				$container.find('> div').each(function(index, child) {
					var $child = $(child);
					if($child.find('.image-widget').length == 1) {
						$child.find('button[data-role="move-up"]').prop('disabled', (index == 0));
						$child.find('button[data-role="move-down"]').prop('disabled', (index == maxIndex));
						$child.find('input[data-role="position"]').val(index);
					}
				});
			};

			$collection.initChild = function($child) {
				var $imageWidget = $child.find('.image-widget');
				if($imageWidget.length == 1) {
					$imageWidget.imageWidget();

					$child.find('button[data-role="remove"]').bind('click', function(e) {
						e.preventDefault();
						if(confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
							$child.remove();
						}
					});

					$child.find('button[data-role="move-up"]').bind('click', function(e) {
						e.preventDefault();
						$child.prev().before($child.detach());
						$collection.updateChilds();
					});

					$child.find('button[data-role="move-down"]').bind('click', function(e) {
						e.preventDefault();
						$child.next().after($child.detach());
						$collection.updateChilds();
					});
				}
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
			var $addButton = $entity.find('button');
			var $select = $entity.find('select');

			if($addButton.length == 1) {
				$addButton.bind('click', function(e) {
					e.preventDefault();
					//console.log('[Entity] Add button click');

					$modal
						.off('hidden.bs.modal')
						.on('hidden.bs.modal', function() {
							$modal.find('textarea.tinymce').each(function() {
								$(this).tinymce().remove();
							});
							$modal.find('.modal-title').html('Modal title');
							$modal.find('.modal-body').empty();
						});

					var path = $(this).data('path');
					$.ajax({
						url: path,
						dataType: 'xml'
					})
					.done(function(xmldata) {
						//console.log('[Entity] Add ajax response');
						//console.log(xmldata);
						var $title = $(xmldata).find('title');
						var $form = $($(xmldata).find('form').text());
						if($title.length == 1) {
							$modal.find('.modal-title').html($title);
						}
						if($form.length == 1) {

							$form.find('.form-footer a.form-cancel-btn').click(function(e) {
								e.preventDefault();
								$modal.modal('hide');
							});

							$form.ajaxForm({
								dataType: 'json',
								success: function(data) {
									//console.log(data);
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

		});
		return this;
	};

	
	$.fn.formWidget = function(params) {

		params = $.extend({}, params);

		this.each(function() {

			/* Selects to select2 */
			$(this).find('select').each(function () {
				var allowclear = $(this).data('allow-clear') == 1 ? true : false;
				$(this).select2({
					allowClear: allowclear
				});
			});

			/* Checkboxes, Radios */
			//$('.form-wrapper input:checkbox, .form-wrapper input:radio').uniform();

			/* Date picker */
			$(this).find('input.date-picker').datepicker().on('changeDate', function (ev) {
	            $(this).datepicker('hide');
	        });

			/* Textarea autosize */
			$(this).find('textarea').not('.tinymce').autosize({append: "\n"});

			/* Image widget */
			$(this).find('.image-widget').imageWidget();

			/* Collections */
			$(this).find('.collection-container').collectionWidget();

			/* Entities */
			$(this).find('.entity_widget').entityWidget();

		});
		return this;
	};

	$(document).ready(function() {

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
		$(document).on('focusin', function(e) {
		    if ($(e.target).closest(".mce-window").length) {
		        e.stopImmediatePropagation();
		    }
		});
	});

})(window.jQuery);