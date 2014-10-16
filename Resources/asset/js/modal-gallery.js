;(function($) { 
	$.fn.modalGallery = function(options) {
		
		var defauts = {
			modalSelector: '#modal-gallery',
			modalDialogSelector: '.modal-dialog',
			modalImageSelector: '.modal-image',
			modalTitleSelector: '.modal-title',
            prevBtnSelector: '.btn-prev',
            nextBtnSelector: '.btn-next',
            thumbSelector: '.thumbnail'
        };

		
	    return this.each(function() {

			var $gallery = $(this);
            var parameters = $.extend(defauts, options, $gallery.data('config'));
            var $thumbs = $gallery.find(parameters.thumbSelector);

            var $modal = $(parameters.modalSelector);
            var $modalDialog = $modal.find(parameters.modalDialogSelector);
            var $modalImage = $modal.find(parameters.modalImageSelector);
            var $modalTitle = $modal.find(parameters.modalTitleSelector);

            var $prevBtn = $modal.find(parameters.prevBtnSelector);
            var $nextBtn = $modal.find(parameters.nextBtnSelector);

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
                $modalImage.empty();
				$modalImage.append($image);
                $modalTitle.html(title);
				if(!modalShown) $modal.modal({show:true});
			};
			
			$gallery.redraw = function() {
				if(imageWidth !== null) {
					windowWidth = $(window).width();
					if(windowWidth > 767) {
						var modalWidth = imageWidth < windowWidth-20 ? imageWidth : windowWidth-20;
                        $modalDialog.css({width: modalWidth});
					}else{
                        $modalDialog.removeAttr('style');
					}
				}
			};
			
			$gallery.showIndex = function(index) {
                if (index !== undefined) {
                    $gallery.index = index;
                }
				if($image !== null) $image.fadeOut(function() { $(this).remove(); });

				var source = $thumbs.eq($gallery.index).attr('href') || null;
				var title = $thumbs.eq($gallery.index).attr('title') || 'Image preview';
				$gallery.loadImage(source, title);
			};
			
			$gallery.prev = function() {
				$gallery.index++;
				if($gallery.index > $thumbs.length-1) $gallery.index = 0;
				$gallery.showIndex();
			};
			
			$gallery.next = function() {
				$gallery.index--;
				if($gallery.index < 0) $gallery.index = $thumbs.length-1;
				$gallery.showIndex();
			};
			
			$gallery.initEvents = function() {
				$thumbs.each(function(index) {
					$(this).click(function(e) {
						e.preventDefault();
						$gallery.showIndex(index);
					});
				});
				if($thumbs.length > 1) {
                    $prevBtn.show().click(function(e) {
						e.preventDefault();
						$gallery.prev();
					});
                    $nextBtn.show().click(function(e) {
						e.preventDefault();
						$gallery.next();
					});
				}else{
                    $prevBtn.hide();
                    $nextBtn.hide();
				}
				$modal.on('show.bs.modal', function() { modalShown = true; });
				$modal.on('hide.bs.modal', function() { modalShown = false; });
				$(window).on('resize', function() { $gallery.redraw(); });
			};
			
			if($thumbs.length > 0) $gallery.initEvents();
	    });
	};
	$('.modal-gallery').modalGallery();
})(window.jQuery);