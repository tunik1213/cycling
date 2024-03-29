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

    commentInput = $('#add-comment-form-sample')
        .clone()
        .removeAttr('id')
        .insertAfter('#comments-container-input');
	initTinyMCE(commentInput);

	$('#comments-container, #comments-list').on('click','button.post-comment',commentPost);
    $('#comments-list').on('click','a.comment-link-reply',commentReply);

    userMenuToggle();

    scrollToHash();

    if($('script#checkCompleted').length > 0) checkCompleted();

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

var initTinyMCE = function(target=undefined,focus=false) {
    params = {
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
        height: 150,
        readonly: false,
        setup: function (editor) {
            editor.on('init', function (e) {
                if(focus) editor.focus();
            });
            editor.on('focus', function(e) {
                if ($(editor.getElement()).is('.restrict')) {
                    login_popup(e,'sight_comment');
                }
            });
        }
    }

    if(target==undefined) {
        //params['selector'] = '.add-comment-form > textarea';
    } else {
        params['target'] = target.find('textarea').first().get(0);
    }

	tinymce.init(params);
}

var commentPost = function (e) {

    e.stopPropagation();
    e.preventDefault();

    tinymce.triggerSave();
    var textarea = $(this).parent().find('textarea');
    var comment_text = textarea.val();
    textarea.val('').blur();
    parent_comment = $(this).closest('.comment');
    
    if (parent_comment.length == 0) {
        parent_id = 0;
        parent_comment = $('#comments-list');
        $('#comments-list-container').removeClass('hidden');
    } else {
        parent_id = parent_comment.attr('comment-id');
    }
    
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
            parent_comment
                .append(response);
            $('#comments-list .add-comment-form')
                .remove();
            tinymce.editors[0].setContent('');
            new_comment = parent_comment.children('.comment').last();
            new_comment.addClass('highlight');
            scrollTop = new_comment.offset().top - 200;
            $([document.documentElement, document.body]).animate({
                scrollTop: scrollTop
            }, 300);
            new_comment.removeClass('highlight', {duration:10000})
        }
    })
}

var commentReply = function(e) {
    e.stopPropagation();
    e.preventDefault();

    $('#comments-list').find('.add-comment-form').remove();

    created = $('#add-comment-form-sample')
        .clone()
        .removeAttr('id')
        .insertAfter(e.target)
        .focus();

    initTinyMCE(created, focus=true);

    created.find('textarea').focus();
}

const login_prompts = {
    sight_comment: 'Необхiдно увiйти в свiй аккаунт для того, щоб прокомментувати цю локацiю'
};

var login_popup = function(event,what) {
    event.preventDefault();
    event.stopPropagation();
    this.blur();
    $.get('/loginmodal', function(form) {
        _form = $(form);
        _form
            .appendTo('body')
            .modal();
        _form
            .find('.login-prompt')
            .html(login_prompts[what])
            ;
    });
}

var scrollToHash = function() {
    if(location.hash == '') return;

    commentHash = location.hash.replace('#','');
    targetElement = $('.comment[scrollto="'+commentHash+'"]');
    if(targetElement.length == 0) return;

    $("html").scrollTop(targetElement.offset().top);

    targetElement.addClass('highlight');
    targetElement.removeClass('highlight', {duration:10000})

}


var userMenuToggle = function(e) {
    $('#userMenu').on('click',function(e){
        $('ul#user-menu-list').toggle();
        e.stopPropagation();
    });

    $(document).click(function(){
      $("#user-menu-list").hide();
    });
}

var checkCompleted = function() {
    $.ajax({
        url: '/visits/verified',
        success: function(res) {
            if(res == 'true') {
                window.location.reload();
            }
        }
    });

    setTimeout(checkCompleted,1000);
}