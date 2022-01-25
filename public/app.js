$(document).ready(function(){

	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
	
	scrollTopButton();

	listTree();

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

var listTree = function() {

	$('#toggle-mobile-filters').click(function(e){
		$('#info-block-sidebar').toggle();
		e.preventDefault();
	});

	$('i.caret').click(function(e){
		$(this).closest('li').toggleClass('active');
	});


	// var toggler = document.getElementsByClassName("caret");
	// var i;

	// for (i = 0; i < toggler.length; i++) {
	//   toggler[i].addEventListener("click", function() {
	//     this.parentElement.querySelector(".nested").classList.toggle("active");
	//     this.classList.toggle("caret-down");
	//   });
	// }
}