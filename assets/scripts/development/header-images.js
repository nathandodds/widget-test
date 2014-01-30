define(['jquery'], function($) {

	var current_page = window.current_page;

	$.getJSON(window.site_path+'core/settings/header-config.json', function(data){
		
		var header_images = data[0];

		if (header_images.hasOwnProperty(current_page)) {

			var header_image = window.site_path+'assets/images/headers/'+header_images[current_page];

			// Do stuff to inject the image into the relevent place!
		}
	});

});