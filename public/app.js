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
    
	$('[data-toggle="tooltip"]').tooltip();

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

function addSightToRoute(e) {
	e.preventDefault();

	const btn = $(this);
	const container = btn.closest('.container');
	const sight = btn.attr('sight-id');
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
	if(alert.length > 0) {
		alert
		.removeClass('alert-danger alert-success alert-warning')
		.addClass(classname)
		.html(message)
		.show();
	} else {
		btn.html('<div class="btn '+classname+'"><i class="fas fa-check"></i> Додано</div>');
	}

	sightImg = btn.closest('.sight-card').find('img');
	animateToCart(sightImg);
	

}

function animateToCart(imgtodrag)
{
    var cart = $('div#right-header');

    if (imgtodrag) {
        if ((imgtodrag.length === 0) || (!imgtodrag.is(':visible')))
            return;

        var imgclone = imgtodrag.clone()
                .offset({
                    top: imgtodrag.offset().top,
                    left: imgtodrag.offset().left
                })
                .css({
                    'opacity': '0.5',
                    'position': 'absolute',
                    'height': imgtodrag.height(),
                    'width': imgtodrag.width(),
                    'z-index': '1000000'
                })
                .appendTo($('body'))
                .animate({
                    'top': cart.offset().top + 10,
                    'left': cart.offset().left + 10,
                    'width': 75,
                    'height': 75
                }, 500);
        imgclone.animate({
            'width': 0,
            'height': 0
        }, function () {
            $(this).remove()
        });
    }
}