$(document).ready(function(){

	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});

	
	scrollTopButton();

});


var scrollTopButton = function() {
	$(window).scroll(function() {
	    if ($(this).scrollTop()) {
	        $('#scroll-top-button').fadeIn();
	    } else {
	        $('#scroll-top-button').fadeOut();
	    }
	});

	$("#scroll-top-button").click(function () {
   		$("html").scrollTop(0);
	});
}