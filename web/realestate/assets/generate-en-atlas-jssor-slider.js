
		jQuery(function($) {
			var jssor_1_options = {
			  $AutoPlay: true,
			  $SlideDuration: 1000,
			  $Idle: 2000,
			  $SlideEasing: $Jease$.$OutQuint,
			  $ArrowNavigatorOptions: {
				$Class: $JssorArrowNavigator$
			  },
			  $BulletNavigatorOptions: {
				$Class: $JssorBulletNavigator$
			  }
			};

			var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

			/* responsive code begin */
			/* you can remove responsive code if you dont want the slider scales while window resizing */
			function ScaleSlider() {
				var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
				if (refSize) {
					refSize = Math.min(refSize, 1920);
					jssor_1_slider.$ScaleWidth(refSize);
				}
				else {
					window.setTimeout(ScaleSlider, 30);
				}
			}
			ScaleSlider();
			$(window).bind("load", ScaleSlider);
			$(window).bind("resize", ScaleSlider);
			$(window).bind("orientationchange", ScaleSlider);
			/* responsive code end */
		});
	