/**
 * Created by Valery on 18.01.2015.
 */
$(function() {
	var $filters = $(".slider-buttons #filters");
	$filters.on("click", "li", function(){
		var $filter = $(this).find("span");
		if (!$filter.hasClass('active')) {
			$filters.find("span.active").removeClass('active');
			$filter.addClass(" active");
			$("#sliderlist div.active").removeClass('active').fadeOut('slow', 'linear', function(){
				($("." + $filter.data('filter'))).fadeIn('slow', 'linear').addClass('active')
			});
		}
	});
});
