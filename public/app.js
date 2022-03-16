$(document).ready(function(){

	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
	
	scrollTopButton();

	listTree();

	$('#search-input').on('keydown', function(e) {
        if (e.keyCode == 13) search($(this).val());
    });

    $('.add-sight-to-route-button').on('click', addSightToRoute);
    $('#map').on('click','.add-sight-to-route-button',addSightToRoute);
    


});

const search = function(query_text) {
	var url = new URL(window.location.href);
	var params = url.searchParams;
	params.set('search',query_text);
	url.search = params.toString();
    window.location.replace(url.toString());
}

const scrollTopButton = function() {
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

const listTree = function() {

	$('#toggle-mobile-filters').click(function(e){
		$('#info-block-sidebar').toggle();
		e.preventDefault();
	});

	$('i.caret').click(function(e){
		$(this).closest('li').toggleClass('active');
	});

}

const setAlert = function(element,classname,message) {
	element
		.removeClass('alert-danger alert-success alert-warning')
		.addClass(classname)
		.html(message)
		.show();
}

function addSightToRoute(e) {
	e.preventDefault();

	const btn = $(this);
	const container = btn.closest('.container');
	const sight = container.attr('sight-id');
	const url = new URL(window.location.href);
	const params = url.searchParams;
	const route = params.get('routeAdd');

	var result = $.ajax('/routes/addSight/',{
		data: {sight: sight,route: route},
		async: false,
	
	});

	if(result.status==200) {
		respData = result.responseJSON;
		message = respData.message;
		classname = respData.success ? 'alert-success' : 'alert-warning';
		if(respData.success) {
			const badgeVal = parseInt($('#my-route-count').html());
			$('#my-route-count').html(badgeVal+1);
		}
	} else {
		message = 'Сталася помилка';
		classname = 'alert-danger';
	}

	alert = container.find('.add-sight-to-route-button-message');
	setAlert(alert,classname,message);

	


	// todo animate moving image to right header

}