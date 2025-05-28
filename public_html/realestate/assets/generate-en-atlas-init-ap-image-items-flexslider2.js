
	function initApImagesItemsSlider() {
		var useRTL = false;

		$(".flexslider-apartment-image").flexslider({
			animation: "slide",
			controlNav: false,
			animationLoop: true,
			slideshow: false,
			rtl: useRTL,
			controlsContainer: $(".custom-controls-container"),
			customDirectionNav: $(".custom-navigation a"),
			initDelay: 0,
			start: function(slider){				
				/*$(".flexslider-apartment-image").removeClass("flexslider-loading-image");*/
				$(".flex-viewport").css({"overflow":"visible"});
				
				var slide_count = slider.count - 1;

				$(slider)
				.find("li:not(.clone)")
				.find("img.lazy:eq(0)")
				.each(function() {
					var src = $(this).attr("data-src");
					$(this).attr("src", src).removeAttr("data-src");
				});
			},
			before: function(slider) { // Fires asynchronously with each slider animation
				var slides = slider.slides,
				  index = slider.animatingTo,
				  $slide = $(slides[index]),
				  $img = $slide.find("img[data-src]"),
				  current = index,
				  nxt_slide = current + 1,
				  prev_slide = current - 1;

				$slide
				.parent()
				.find("li:not(.clone)")
				.find('img.lazy:eq(' + current + '), img.lazy:eq(' + prev_slide + '), img.lazy:eq(' + nxt_slide + ')')
				.each(function() {
					var src = $(this).attr("data-src");
					$(this).attr("src", src).removeAttr("data-src");
				});
			}
		});
	}
