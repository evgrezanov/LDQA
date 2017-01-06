$(function(){
	
	$('#menu_btn').click(function(){
		$('#menu > ul').slideToggle();
	});
	
	var $window = $(window), line = $('.top_line');
	
	
	$window.bind('scroll', function() {
		
		if ($window.scrollTop() > 100) {
			line.addClass('active');
		} else {
			line.removeClass('active');
		}
		
	});
	
	
	function scrollTo(element, offset, hash) {
		
		if (!hash | hash.charAt(0) != '#') hash = false;
		
		if (!(element instanceof jQuery)) {
			if (hash == false && element.charAt(0) == '#') hash = element;
			element = $(element);
		}
		
		if (element.length) {
			$('html, body').stop().animate({
				'scrollTop': element.offset().top + offset
			}, 1500, function() {
				if (hash) window.location.hash = hash;
			});
		}
		
		return false;
		
	}
	
	$('.top_line .item.block_r .btn_green').click(function(){
		$('#modal, #popup_1').fadeIn();
		$('#popup_1').css('top', $(window).scrollTop() + 100);
	});
	
	$('#modal, .popup .close').click(function(){ 
		$('#modal, .popup').fadeOut();
	});
	
	$(document).keyup(function(e){ 
		if (e.which == 27) {
			$('#modal, .popup').fadeOut();
		}
	});
	
});