jQuery(document).ready(function(){	
	jQuery(".hentry, .widget, .featured-content, .featured-item-container").fitVids();
	jQuery('table').addClass('table');
	jQuery('table').wrap('<div class="table-responsive" />');
	jQuery('.nav-bar li:has(ul)').addClass('menu-item-has-child');
	
	jQuery(function(){ 
		if( tdminimalParams.smoothScroll == true && jQuery.fn.niceScroll ) {
			jQuery("html").niceScroll({
				scrollspeed: 60,
				mousescrollstep: 35,
				cursorwidth: 10,
				cursorborder: 0,
				cursorcolor: '#eeeeee',
				cursorborderradius: 0,
				horizrailenabled: false
			});		
		}
	});
	
	jQuery(function() {
		if( tdminimalParams.backgroundImages && jQuery.fn.backstretch ) {
			jQuery.backstretch(JSON.parse(tdminimalParams.backgroundImages), {duration: 5500, fade: 750});
		}
	});
	
	jQuery(function() {
		if( tdminimalParams.isMasonryLayout != false ) {
			var blogLayoutMode = 'sloppyMasonry';
		} else {
			var blogLayoutMode = 'fitRows';
		}
		
		jQuery(document).imagesLoaded(function(){
			var blogContainer = jQuery('.content-grid');
			blogContainer.isotope({
				itemSelector : '.post-box',
				layoutMode : blogLayoutMode
			});
	
			 jQuery(window).smartresize(function(){
				jQuery('.content-grid').isotope({
					itemSelector : '.post-box',
					layoutMode : blogLayoutMode
				});
			});
		});
	});
	
	postThumbHover = function() {
		jQuery('.post-thumb').hover(function(){
			jQuery(this).find('.thumb-mask').stop().fadeTo(100, 0.8, function () {
				jQuery(this).parent().find('.thumb-mask-link-icon').fadeIn(100).animate({
					'top' : '50%',
					'margin-top': '-30.5px'
				}, 100);
			});
		},function(){
			jQuery(this).parent().find('.thumb-mask-link-icon').fadeOut(10).css({'top':'0'});
			jQuery('.thumb-mask').stop().fadeTo(100, 0);
		});
	};
	postThumbHover();
	
	jQuery(function() {
		jQuery(document).on('click', '#header-search-button', function(e){
			e.preventDefault();
			if( jQuery('body').hasClass('tablet-site-content') ) { 
				jQuery('.header-search-button').fadeOut(300, function(){
					jQuery('.header-search-box').fadeIn();
				});	
			} else if( jQuery('body').hasClass('phone-site-content') ) {
				jQuery('.header-search-button').fadeOut(300, function(){
					jQuery('.header-search-box').fadeIn();
				});		
			} else { 
				jQuery('.header-search-button, .nav-bar').fadeOut(300, function(){
					jQuery('.header-search-box').fadeIn();
				});
			}
		});
		
		jQuery(document).on('click', '#header-search-button-hide', function(e) {
			e.preventDefault();
			jQuery('.header-search-box').fadeOut(300, function() {
				if( jQuery('body').hasClass('tablet-site-content') ) {  
					jQuery('.header-search-button').fadeIn();
				} else if( jQuery('body').hasClass('phone-site-content') ) {
					jQuery('.header-search-button').fadeIn();
				} else {
					jQuery('.header-search-button, .nav-bar').fadeIn();
				}
			});
		});
	});
	
	jQuery(function() {
    	if ( tdminimalParams.fixedMenu == 1 ) {
    	
			var stickyNavigationContainer = jQuery('#site-navigation');
			var stickyNavigationTop = stickyNavigationContainer.offset().top;  
	
			var stickyNavigation = function() {  
				var scrollTop = jQuery(window).scrollTop(); 
		
				if (scrollTop > stickyNavigationTop) {   
					stickyNavigationContainer.addClass('sticky-navigation'); 
				} else {  
					stickyNavigationContainer.removeClass('sticky-navigation');   
				} 
				
				if( jQuery('#wpadminbar').is(':visible') && jQuery('#site-navigation').is('.sticky-navigation') ) {
					var adminBarHeight = jQuery('#wpadminbar').css('height');
					jQuery('#site-navigation').css('top', adminBarHeight);		
				} else {
					jQuery('#site-navigation').css('top', '0');
				}
			};
	
			stickyNavigation();  

			jQuery(window).scroll(function() {  
				stickyNavigation();  
			}); 
		}
	});
	
	jQuery(function(){
		jQuery(window).scroll(function() {
			if(jQuery(this).scrollTop() > 300) {
				jQuery('#gotop').fadeIn();	
			} else {
				jQuery('#gotop').fadeOut();
			}
		});
	
		jQuery('#gotop').click(function () {
			jQuery('body,html').animate({
				scrollTop: 0
			}, 800, "easeOutExpo");
			return false;
		});
	});
	
	jQuery(function() { 
		jQuery(window).imagesLoaded(function(){
			jQuery('.post-slideshow .bxslider, .widget .bxslider').bxSlider({
				pager: false,
				easing: 'easeOutQuart',
				mode: 'fade',
				useCSS: false,
				speed: 500,
				preloadImages:'all',
				adaptiveHeight: true,
				autoHover: true,
				pause: 7000,
				prevText: '<i class="fa fa-angle-left"></i>',
				nextText: '<i class="fa fa-angle-right"></i>',
				onSliderLoad: function() {
					jQuery('.post-slideshow').find('.bxslider').css("visibility", "visible").fadeTo(200, 1);
					jQuery(document).resize();
				},
				onSlideAfter: function() {
					jQuery('.content-grid').isotope();
					jQuery('.content-grid').isotope('reLayout');
				}
			});
		});	
	});	
	
	var bodyContainer = jQuery('body');
	smallScreen = function() {
		var browserWidth = jQuery( window ).width();
		
		if( browserWidth < 1200 && browserWidth > 979 ) { 
			bodyContainer.addClass( 'large-site-content' );
		} else {
			bodyContainer.removeClass( 'large-site-content' );
		}
		
		if( browserWidth < 979 && browserWidth > 768 ) { 
			bodyContainer.addClass( 'default-site-content' );
		} else {
			bodyContainer.removeClass( 'default-site-content' );
		}
	
		if( browserWidth < 768 && browserWidth > 480) {
			bodyContainer.addClass( 'tablet-site-content' );
		} else {
			bodyContainer.removeClass( 'tablet-site-content' );
		}
		
		if( browserWidth < 480) {
			bodyContainer.addClass( 'phone-site-content' );
		} else {
			bodyContainer.removeClass( 'phone-site-content' );
		}
	}
	
	smallScreen();
	
	jQuery(window).resize(function() {
		smallScreen();
	});
	
	jQuery(function(){
    	if( jQuery('.share-buttons-container').length ) {
    		jQuery('a.facebook, a.twitter, a.googleplus, a.linkedin, a.pinterest').on('click', function(e) {
    			e.preventDefault();
    			var left  = (jQuery(window).width()/2)-(900/2),
					top   = (jQuery(window).height()/2)-(600/2);
    			var newwindow=window.open(jQuery(this).attr('href'),'',"height=400,width=600,top="+top+", left="+left+"");
        		if (window.focus) {newwindow.focus()}
    		});
    	}
    });
    
    jQuery(function(){
    	var breakingNewsContainer = jQuery('#breaking-news');
    	if( breakingNewsContainer.length ) {
    		var breakingNewsLeft = breakingNewsContainer.find('.breaking-news-title').outerWidth() + 6;
    		jQuery('.breaking-news-items .bxslider').bxSlider({
				mode: 'vertical',
				pager: false,
				controls: false,
				auto: true,
				preloadImages:'all',
				useCSS: false,
				autoHover: true,
				pause: 4000,
				onSliderLoad: function() {
					breakingNewsContainer.find('.bxslider').css("visibility", "visible");
					breakingNewsContainer.find('.bx-viewport').css('left', breakingNewsLeft + 'px');
				}
			});
    	}
    });
    
    jQuery(function(){
    	var groupedPostsWidget = jQuery('.widget_tdminimal_grouped_posts_widget');
    	if( groupedPostsWidget.length ) {
    		groupedPostsWidget.find('.widget-title a').click(function(e) {
    			e.preventDefault();
    			groupedPostsWidget.find('.grouped-posts, .widget-title a').removeClass('active');
    			groupedPostsWidget.find( jQuery(this).attr('href') ).addClass('active');
    			jQuery(this).addClass('active');
    			
    		});
    	}
    });
	
	jQuery(function(){
    	if( typeof(tdminimalParams) != "undefined" && tdminimalParams !== null && jQuery.fn.infinitescroll !== undefined) {
    		var gridContainer = jQuery('.content-grid');
	
			gridContainer.infinitescroll({
				navSelector  : '#nav-below',  
				nextSelector : '#nav-below .nav-previous a',  
				itemSelector : '.post-box',  
				loading: {
					speed: 'slow',
					img: tdminimalParams.infinitescrollImg,
					msgText: tdminimalParams.infinitescrollLoadMsg,
					finishedMsg: tdminimalParams.infinitescrollFinishedMsg
				}
			},
			function( newElements ) {				
				var newElems = jQuery( newElements ).css({ opacity: 0 });
				jQuery(".hentry, .widget").fitVids();
				jQuery('video, audio').mediaelementplayer();
				postThumbHover();

				newElems.imagesLoaded(function(){ 
					newElems.animate({ opacity: 1 });
					newElems.find('.bxslider').bxSlider({
						pager: false,
						easing: 'easeOutQuart',
						mode: 'fade',
						useCSS: false,
						speed: 500,
						preloadImages:'all',
						adaptiveHeight: true,
						autoHover: true,
						pause: 7000,
						prevText: '<i class="fa fa-angle-left"></i>',
						nextText: '<i class="fa fa-angle-right"></i>',
						onSliderLoad: function() {
							jQuery('.post-slideshow').find('.bxslider').css("visibility", "visible").fadeTo(200, 1);
							jQuery(document).resize();
						},
						onSlideAfter: function() {
							jQuery('.content-grid').isotope();
							jQuery('.content-grid').isotope('reLayout');
						}
					});
					gridContainer.isotope(); 
					gridContainer.isotope( 'appended', jQuery( newElements ) ); 
				});
				
			});
    	}
    });		
    
    setTimeout( function() {
		jQuery(document).resize();
  	}, 1000);
});