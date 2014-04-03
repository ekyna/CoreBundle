;(function($) { 
	$.fn.modalGallery = function(options) {
		
		var defauts = {
			modalId: 'modal-gallery'
        };
		var parameters = $.extend(defauts, options);
		
	    return this.each(function() {
			
			var $gallery = $(this);
			var $links = $gallery.find('a.thumbnail');
			var $modal = $('#'+parameters.modalId);
			var $modalImage = $modal.find('.modal-image');
			var $image = null;
			var imageWidth = null;
			var modalShown = false;
			
			$gallery.loader = null;
			$gallery.index = 0;
			
			$gallery.loadImage = function(source, title) {
				if(source === null) return;
				if($gallery.loader !== null) {
					$gallery.loader.onload = $gallery.loader.onerror = null;
				}
				$gallery.loader = loadImage(
					source,
					function (img) {
						$gallery.showImage(img, title);
					}
				);
			};
			
			$gallery.showImage = function(img, title) {
				imageWidth = img.width;
				$gallery.redraw();
				$image = $(img).fadeIn();
				$modalImage.append($image);
				$modal.find('.modal-title').html(title);
				if(!modalShown) $modal.modal({show:true});
			};
			
			$gallery.redraw = function() {
				if(imageWidth !== null) {
					windowWidth = $(window).width();
					if(windowWidth > 767) {
						var modalWidth = imageWidth < windowWidth-20 ? imageWidth : windowWidth-20;
						$modal.find('.modal-dialog').css({width: modalWidth});
					}else{
						$modal.find('.modal-dialog').removeAttr('style');
					}
				}
			};
			
			$gallery.showIndex = function(index) {
				$gallery.index = index || $gallery.index;
				if($image !== null) $image.fadeOut(function() { $(this).remove(); });
				$modalImage.empty();
				var source = $links.eq($gallery.index).attr('href') || null;
				var title = $links.eq($gallery.index).attr('title') || 'Image preview';
				$gallery.loadImage(source, title);
			};
			
			$gallery.prev = function() {
				$gallery.index++;
				if($gallery.index > $links.length-1) $gallery.index = 0;
				$gallery.showIndex();
			};
			
			$gallery.next = function() {
				$gallery.index--;
				if($gallery.index < 0) $gallery.index = $links.length-1;
				$gallery.showIndex();
			};
			
			$gallery.initEvents = function() {
				$links.each(function(index) {
					$(this).click(function(e) {
						e.preventDefault();
						$gallery.showIndex(index);
					});
				});
				if($links.length > 1) {
					$modal.find('button.btn-prev').show().click(function(e) {
						e.preventDefault();
						$gallery.prev();
					});
					$modal.find('button.btn-next').show().click(function(e) {
						e.preventDefault();
						$gallery.next();
					});
				}else{
					$modal.find('button.btn-prev').hide();
					$modal.find('button.btn-next').hide();
				}
				$modal.on('show.bs.modal', function() { modalShown = true; });
				$modal.on('hide.bs.modal', function() { modalShown = false; });
				$(window).on('resize', function() { $gallery.redraw(); });
			};
			
			if($links.length > 0) $gallery.initEvents();
	    });
	};
	$('.modal-gallery').modalGallery();
})(window.jQuery);