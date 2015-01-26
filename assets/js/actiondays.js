/**
 * Created by Valery on 18.01.2015.
 */
$(function() {
	$( ".slider-buttons li").on("click", function() {
		var that = $(this);
		$("#sliderlist div.active").removeClass('active').fadeOut('slow', 'linear', function(){
			$filter = that.find("span").data('filter');
			($("." + $filter)).fadeIn('slow', 'linear').addClass('active')
		});
	})
});
