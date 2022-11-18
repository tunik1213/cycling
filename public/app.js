$(document).ready(function(){

	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': csrf_token()
	    }
	});
	
	scrollTopButton();

	listTree();

	$('#search-input').on('keydown', function(e) {
        if (e.keyCode == 13) search($(this).val());
    });

    $('.add-sight-to-route-button').on('click', addSightToRoute);
    $('#map-preview').on('click','.add-sight-to-route-button',addSightToRoute);
    
	$('[data-toggle="tooltip"]').tooltip();

	initTinyMCE();

	$('#comments-container, #comments-list').on('click','button.post-comment',commentPost);
});

const csrf_token = function() {
    return $('meta[name="csrf-token"]').attr('content');
}

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
	        $('#scroll-prompt').hide();
	    } else {
	        $('#scroll-top-button').fadeOut();
	        $('#scroll-prompt').show();
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
		btn.html('<div class="btn btn-info '+classname+'"><i class="fas fa-check"></i>'+message+'</div>');
	}

	sightImg = btn.closest('.sight-container').find('img.sight-image');
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
                    'min-width': '0',
                    'z-index': '1000000'
                })
                .appendTo($('body'))
                .animate({
                    'top': cart.offset().top + 10,
                    'left': cart.offset().left + 10,
                    'width': '75px',
                    'height': '75px'
                }, 500);
        imgclone.animate({
            'width': 0,
            'height': 0
        }, function () {
            $(this).remove()
        });
    }
}

var uploadImage = function(blobInfo, success, failure) {
    var xhr, formData;

    xhr = new XMLHttpRequest();
    xhr.withCredentials = false;
    xhr.open('POST', '/upload');

    xhr.onload = function() {
        var json;

        if (xhr.status != 200) {
            failure('HTTP Error: ' + xhr.status);
            return;
        }

        json = JSON.parse(xhr.responseText);

        if (!json || typeof json.url != 'string') {
            failure('Invalid JSON: ' + xhr.responseText);
            return;
        }

        success(json.url);
    };

    formData = new FormData();
    formData.append('filename', blobInfo.blob(), blobInfo.filename());
    formData.append('_token',csrf_token());

    xhr.send(formData);
}

var initTinyMCE = function() {
	tinymce.init({
        selector: '.add-comment-form > textarea',
        language: 'uk',
        plugins: 'link, emoticons, paste, textcolor, image',
        paste_as_text: true,
        toolbar: 'undo redo | bold italic removeformat | forecolor backcolor | charmap emoticons link image media',
        menubar: false,
        file_picker_types: 'file image media',
        images_upload_url: '/upload',
        automatic_uploads: true,
        images_upload_handler: uploadImage,
        contextmenu: false,
        browser_spellcheck: true,
        relative_urls: false,
        height: 150
    });
}

var commentPost = function (e) {

    e.stopPropagation();
    e.preventDefault();

    tinymce.triggerSave();
    var textarea = $(this).parent().find('textarea');
    var comment_text = textarea.val();
    textarea.val('').blur();
    var parent_id = $(this).closest('.comment').attr('comment-id');
    if (parent_id === undefined) parent_id = 0;

    parent_container = $('[comment-id="' + parent_id + '"] > .comment-children');
    if (parent_container.length === 0)
        parent_container = $('#comments-list');

    commentable_type = $('#comments-container').attr('object-type');
    commentable_id = $('#comments-container').attr('object-id');

    $.ajax({
        url: "/comments/add",
        async: false,
        data: {
            _token:csrf_token(),
            comment:comment_text,
            commentable_id:commentable_id,
            commentable_type: commentable_type,
            parent_id: parent_id
        },
        method: "POST",
        success: function (response) {
            parent_container
                .append(response);
            $('#comments-list .add-comment-form')
                .remove();
            tinymce.editors[0].setContent('');
            new_comment = parent_container.children('.comment').last().addClass('new-comment');
            new_comment.addClass('new-comment');
            scrollTop = new_comment.offset().top - 200;
            $([document.documentElement, document.body]).animate({
                scrollTop: scrollTop
            }, 300);
            new_comment.removeClass('new-comment', {duration:10000})
        }
    })
}

