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

function init_map() {
	var var_location = new google.maps.LatLng(48.194999, 11.249539);

	var var_mapoptions = {
		center: var_location,
		zoom: 14
	};

	var var_marker = new google.maps.Marker({
		position: var_location,
		map: var_map,
		title:"Playhouse Fürstenfeldbruck"});

	var var_map = new google.maps.Map(document.getElementById("map"),
		var_mapoptions);

	var_marker.setMap(var_map);

}


jQuery(document).ready(function () {
	jQuery('#mygallery').justifiedGallery({
		rowHeight: 200,
		fixedHeight: false,
		lastRow: 'nojustify',
		margins: 10,
		randomize: false,
		sizeRangeSuffixes: {
			'lt100':'',
			'lt240':'',
			'lt320':'',
			'lt500':'',
			'lt640':'',
			'lt1024':''
		}
	}).on('jg.complete', function () {
		$('#mygallery a').swipebox();
	});
});



