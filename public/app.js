$(document).ready(function(){
	scrollTopButton();

	


})


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