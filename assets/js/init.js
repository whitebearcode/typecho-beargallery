$(document).ready(function(){
	"use strict";
	$(window).on('scroll', function(e) {
		e.preventDefault();	
        bgallery_fl_totop_myhide();
    });
	
	bgallery_fl_next_slide_button();
	bgallery_fl_vertical_menu_scroll();
	bgallery_fl_sharing_social_icons();
	bgallery_fl_totop();
	bgallery_fl_sticky_sidebar();
	bgallery_fl_menu_on();
	bgallery_fl_vertical_menu_height_regulation();
	bgallery_fl_img_height_regulation();
	bgallery_fl_slider();
	bgallery_fl_vertical_menu_trigger();
	
	var pjax_id = '#pjax';
		$(document).pjax('a[target!=_blank][rel!=group][pjax!=no]', pjax_id, {
			fragment: pjax_id,
			timeout: 50000
		});
		$(document).on('pjax:start',
		function() {
			NProgress.start();
		});
		$(document).on('pjax:end',
		function() {
			NProgress.done();
		});
		$(document).on('pjax:complete',
		function() {
		   $('img.lazyload')
  .visibility({
    type       : 'image',
    transition : 'fade in',
    duration   : 1000,
  });
			bgallery_fl_next_slide_button();
	bgallery_fl_vertical_menu_scroll();
	bgallery_fl_sharing_social_icons();
	bgallery_fl_totop();
	bgallery_fl_sticky_sidebar();
	bgallery_fl_menu_on();
	bgallery_fl_vertical_menu_height_regulation();
	bgallery_fl_img_height_regulation();
	bgallery_fl_slider();
	bgallery_fl_vertical_menu_trigger();
		});
		
	$(window).resize(function() {
        bgallery_fl_img_height_regulation();
		bgallery_fl_vertical_menu_scroll();
		bgallery_fl_vertical_menu_height_regulation();
    });
	
	
});


function bgallery_fl_next_slide_button(){
	"use strict";
	$('.bgallery_fl_slider .bgallery_fl_overlay').on('click', function() {
		setTimeout(function(){
			$('.flexslider').flexslider('next');
		}, 100);
		});
		
}


function bgallery_fl_vertical_menu_scroll(){
	"use strict";
	var H = $( window ).height();

	
	$('.bgallery_fl_vertical_menu .scrollable').css({height:H});
	$('.bgallery_fl_vertical_menu .scrollable').niceScroll({
		touchbehavior:false,
		cursorwidth:0,
		autohidemode:true,
		cursorborder:"0px solid #fff"
	});

} 



function bgallery_fl_sharing_social_icons(){
	"use strict";
	$('.bgallery_fl_gallery_single_in .img_list_nth .title a').on('click', function(e) {
		e.preventDefault();
		
		var social_list = $(this).parent().parent().find('.share_social');
		

		var vrem = $('.bgallery_fl_gallery_single_in .img_list_nth .title');
		vrem.removeClass('opened');
		vrem.find('a').removeClass('opened');
		vrem.parent().find('.share_social').removeClass('opened');
		
		if(!$(this).hasClass('opened')){
			$(this).addClass('opened');
			$(this).parent().addClass('opened');
			setTimeout(function(){
				social_list.addClass('opened');
			}, 500);
		}else{
			$(this).removeClass('opened');
			$(this).parent().removeClass('opened');
			social_list.removeClass('opened');
		}
		return false;
	});
}


function bgallery_fl_totop_myhide(){
	"use strict";
	var totop		=	$("a.totop");
	var topoffset 	= 	totop.offset().top;
	
	if(topoffset > 1000){
		totop.addClass('opened');	
	}else{
		totop.removeClass('opened');	
	}
}


function bgallery_fl_totop(){
	"use strict";
	$("a.totop").on('click', function(e) {
		e.preventDefault();		
		$("html, body").animate({ scrollTop: 0 }, 'slow');
		return false;
	});
}


function bgallery_fl_sticky_sidebar(){

	"use strict";
	
	$('.sticky_sidebar').theiaStickySidebar({
		containerSelector: '', // The sidebar's container element. If not specified, it defaults to the sidebar's parent.
		additionalMarginTop: 20,
		additionalMarginBottom: 0,
		updateSidebarHeight: true, // Updates the sidebar's height. Use this if the background isn't showing properly, for example.
		minWidth: 1040, // The sidebar returns to normal if its width is below this value. 
	});
	
}


function bgallery_fl_menu_on(){
	"use strict";
	$('.bgallery_fl_menu_trigger a').on('click', function(e) {
		e.preventDefault();
 

		if(!$(this).hasClass('opened')){
			$('.bgallery_fl_menu_trigger').addClass('opened');
			setTimeout(function(){
				$('.bgallery_fl_vertical_menu').addClass('opened');	
			}, 500);
			
		}else{
			$('.bgallery_fl_vertical_menu').removeClass('opened');
		}
		return false;
	});
}


function bgallery_fl_vertical_menu_height_regulation(){
	"use strict";
	
	var H 		= $( window ).height();
	var menu	= $('.bgallery_fl_vertical_menu');
	
	
	menu.css({height:H});
	
}



function bgallery_fl_img_height_regulation(){
	"use strict";
	
	var H 		= $( window ).height();
	var list	= $('.bgallery_fl_slider .flexslider ul.slides li');
	
	list.css({height:H});
}


function bgallery_fl_slider(){
	"use strict";
	
	$('.flexslider').flexslider({
		animation: "fade",
		controlNav: false, 
    	directionNav: true,
		slideshowSpeed: 4000,
          
	});


}


function bgallery_fl_vertical_menu_trigger(){
	"use strict";
	$('.bgallery_fl_vertical_menu_in span.vertical_menu_closer a').on('click', function(e) {
		e.preventDefault();
		
		if($(this).hasClass('opened')){
			$(this).removeClass('opened');
			$(this).addClass('opened');
			$('.bgallery_fl_vertical_menu').addClass('opened');
		}else{
			$(this).removeClass('opened');
			$('.bgallery_fl_vertical_menu').removeClass('opened');
			$('.bgallery_fl_menu_trigger').removeClass('opened');
		}
		return false;
	});
}

console.log("\n %c Beargallery v1.0.5 %c https://github.com/whitebearcode/typecho-beargallery \n","border-radius:10px 0 0 10px;color: #fadfa3; background: #030307; padding:5px 0;","border-radius:0 10px 10px 0;background: #fadfa3; padding:5px 0;");