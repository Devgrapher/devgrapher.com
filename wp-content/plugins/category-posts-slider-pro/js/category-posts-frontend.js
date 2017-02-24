// JavaScript Document
var r = jQuery.noConflict();
		r(document).ready(function(){	
                   						
                        r('#cpspCategoryTabs').easyResponsiveTabs({
                            type: CpspAjax.tabtype, //Types: default, vertical, accordion
                            width: 'auto', //auto or any width like 600px
                            fit: true, // 100% fit in a container
                            tabidentify: 'hor_1', // The tab groups identifier
                           	activetab_bg: CpspAjax.activetab_bg, // background color for active tabs in this group
							activetab_fontcolor: CpspAjax.activetab_fontcolor, // background color for active tabs in this group
							inactive_bg: CpspAjax.inactive_bg, // background color for inactive tabs in this group
							active_border_color: CpspAjax.active_border_color, // border color for active tabs heads in this group
							active_content_border_color: CpspAjax.active_content_border_color,
                            activate: function(event) { // Callback function if tab is switched
                                //var id = r(this).attr('id');
                                var tabid = r('.resp-tabs-container').find(".resp-tab-content-active");
                                var id = tabid.attr('data-contentid');
                                var cat_id = tabid.attr('data-catid');
								nonce = CpspAjax.ajax_nonce;
                                //alert(nonce);
								r('#cpsp-tabs-slide'+id).empty();
                                r('#cpsp-tabs-slide'+id).append('<div style="margin:0 auto;padding:30px;"><div class="loader"></div></div>');
                                
                                
                                r.ajax({
								type: "POST",
								url: CpspAjax.ajaxUrl,		
								data : {action: "cpsp_load_tabcontent", id : id, category : cat_id, nonce: nonce},		
								success: function(response){
                                   // alert(response);
                                       r('#cpsp-tabs-slide'+id).empty().append(response);
                                        var tab_num = r('#cpsp-tabs-slide'+id).find("#cpsp_sliderpro");
                                        tab_num.sliderPro({
										width: 960,
										height: 500,
										orientation: CpspAjax.orientation,
										loop: JSON.parse(CpspAjax.loop),
										thumbnailsPosition: CpspAjax.thumbnailsPosition,																			
										fade: JSON.parse(CpspAjax.fade),
										arrows: JSON.parse(CpspAjax.arrows),
										buttons: JSON.parse(CpspAjax.buttons),										
										shuffle: JSON.parse(CpspAjax.slideshuffle),
										smallSize: CpspAjax.smallSize,
										mediumSize: CpspAjax.mediumSize,
										largeSize: CpspAjax.largeSize,
										thumbnailArrows: JSON.parse(CpspAjax.thumbnailArrows),
										autoplay: JSON.parse(CpspAjax.autoplay),									
										autoplayDirection:CpspAjax.autoplayDirection,
										autoplayOnHover:CpspAjax.autoplayOnHover,
										slideAnimationDuration: CpspAjax.slideAnimationDuration,
										keyboard: JSON.parse(CpspAjax.keyboard),
										});
								}
							}); 
                            }
                        });
                        
                        //default first tab
						
                         r( '#cpsp_sliderpro' ).sliderPro({
                                    	width: 960,
										height: 500,
										orientation: CpspAjax.orientation,
										loop: JSON.parse(CpspAjax.loop),
										thumbnailsPosition: CpspAjax.thumbnailsPosition,																			
										fade: JSON.parse(CpspAjax.fade),
										arrows: JSON.parse(CpspAjax.arrows),
										buttons: JSON.parse(CpspAjax.buttons),										
										shuffle: JSON.parse(CpspAjax.slideshuffle),
										smallSize: CpspAjax.smallSize,
										mediumSize: CpspAjax.mediumSize,
										largeSize: CpspAjax.largeSize,
										thumbnailArrows: JSON.parse(CpspAjax.thumbnailArrows),
										autoplay: JSON.parse(CpspAjax.autoplay),									
										autoplayDirection:CpspAjax.autoplayDirection,
										autoplayOnHover:CpspAjax.autoplayOnHover,
										slideAnimationDuration: CpspAjax.slideAnimationDuration,
										keyboard: JSON.parse(CpspAjax.keyboard),
                            });
		});