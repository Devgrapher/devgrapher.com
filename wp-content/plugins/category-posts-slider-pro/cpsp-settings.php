<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
	
	if(!function_exists('cpsp_load_settings')){
		function cpsp_load_settings(){
			global $wpdb;
			//Get default data from options table
			$cpsp_plugin_defaults = get_option( CPSP_SLIDER_SLUG.'_options' );
			$cpsp_defaults = "";
			if(!empty($cpsp_plugin_defaults))
			{
				$cpsp_defaults = unserialize($cpsp_plugin_defaults);
				
				$data = (object) $cpsp_defaults;
				
			}	
			else
			{
				$data = cpsp_loadSliderDefaults();
			}
			//print_r($data);
			$data_tab_type = $data->tab_type;
			$data_activetab_bg = $data->activetab_bg;
			$data_activetab_fontcolor = $data->activetab_fontcolor;
			$data_inactive_bg = $data->inactive_bg;
			$data_active_border_color = $data->active_border_color;
			$data_active_content_border_color = $data->active_content_border_color;		
			$data_orientation = $data->orientation;
			$data_loop = $data->loop;
			$data_slideAnimationDuration = $data->slideAnimationDuration;
			$data_fade = $data->fade;
			$data_arrows = $data->arrows;
			$data_buttons = $data->buttons;
			$data_keyboard = $data->keyboard;
			$data_slideshuffle = $data->slideshuffle;
			$data_smallSize = $data->smallSize;
			$data_mediumSize = $data->mediumSize;
			$data_largeSize = $data->largeSize;
			$data_thumbnailsPosition = $data->thumbnailsPosition;			
			$data_thumbnailArrows = $data->thumbnailArrows;
			$data_autoplay = $data->autoplay;
			$data_autoplayDirection = $data->autoplayDirection;
			$data_autoplayOnHover = $data->autoplayOnHover;
			$data_post_title_show = $data->post_title_show;
			$data_post_link_title = $data->post_link_title;
			$data_post_excerpt_show = $data->post_excerpt_show;
			$data_post_excerpt_characters = $data->post_excerpt_characters;	
			$data_post_excerpt_readmore = $data->post_excerpt_readmore;			
			
			if ( isset( $_POST['cpsp-setting-nonce'] ) ) {
				if ( ! wp_verify_nonce( $_POST['cpsp-setting-nonce'], 'cpsp-setting-save-nonce' ) ) {
				return;
				}
			}
			
			if ( ! current_user_can( 'manage_options') ) {
			return;
			}
			if(isset($_REQUEST['submit'])):
			/*echo '<pre>';
			print_r($_POST);
			echo '</pre>';*/
			$data_tab_type = sanitize_text_field($_POST['tabtype']);
			$data_activetab_bg = sanitize_text_field($_POST['activetab_bg']);
			$data_activetab_fontcolor = sanitize_text_field($_POST['activetab_fontcolor']);
			$data_inactive_bg = sanitize_text_field($_POST['inactive_bg']);
			$data_active_border_color = sanitize_text_field($_POST['active_border_color']);
			$data_active_content_border_color = sanitize_text_field($_POST['active_content_border_color']);
			$data_orientation = sanitize_text_field($_POST['orientation']);
			if(isset($_POST['loop'])){$data_loop = 'true';	}else{$data_loop = 'false';}					
			$data_slideAnimationDuration = sanitize_text_field($_POST['slideAnimationDuration']);
			if(isset($_POST['fade'])){$data_fade = 'true';	}else{$data_fade = 'false';}
			if(isset($_POST['arrows'])){$data_arrows = 'true';	}else{$data_arrows = 'false';}	
			if(isset($_POST['buttons'])){$data_buttons = 'true';	}else{$data_buttons = 'false';}		
			if(isset($_POST['keyboard'])){$data_keyboard = 'true';	}else{$data_keyboard = 'false';}		
			if(isset($_POST['slideshuffle'])){$data_slideshuffle = 'true';	}else{$data_slideshuffle = 'false';}				
			$data_smallSize = sanitize_text_field($_POST['smallSize']);
			$data_mediumSize = sanitize_text_field($_POST['mediumSize']);
			$data_largeSize = sanitize_text_field($_POST['largeSize']);
			$data_thumbnailsPosition = sanitize_text_field($_POST['thumbnailsPosition']);			
			if(isset($_POST['thumbnailArrows'])){$data_thumbnailArrows = 'true';	}else{$data_thumbnailArrows = 'false';}
			if(isset($_POST['autoplay'])){$data_autoplay = 'true';	}else{$data_autoplay = 'false';}
			$data_autoplayDirection = sanitize_text_field($_POST['autoplayDirection']);
			$data_autoplayOnHover = sanitize_text_field($_POST['autoplayOnHover']);
			if(isset($_POST['post_title_show'])){$data_post_title_show = 'true';	}else{$data_post_title_show = 'false';}
			if(isset($_POST['post_link_title'])){$data_post_link_title = 'true';	}else{$data_post_link_title = 'false';}
			if(isset($_POST['post_excerpt_show'])){$data_post_excerpt_show = 'true';	}else{$data_post_excerpt_show = 'false';}			
			$data_post_excerpt_characters = sanitize_text_field($_POST['post_excerpt_characters']);
			if(isset($_POST['post_excerpt_readmore'])){$data_post_excerpt_readmore = 'true';	}else{$data_post_excerpt_readmore = 'false';}
			
			$cpsp_settings_arr = array(
			'tab_type'            			=> $data_tab_type,
            'activetab_bg'        			=> $data_activetab_bg,
			'activetab_fontcolor'			=> $data_activetab_fontcolor,
            'inactive_bg'         			=> $data_inactive_bg,			
            'active_border_color' 			=> $data_active_border_color,            
            'active_content_border_color'  	=> $data_active_content_border_color,           
            'orientation' 					=> $data_orientation,
			'loop' 							=> $data_loop,
			'slideAnimationDuration' 		=> $data_slideAnimationDuration,			       
            'fade'     						=> $data_fade,            
            'arrows'          				=> $data_arrows,
            'buttons'           			=> $data_buttons,
			'keyboard'           			=> $data_keyboard,
            'slideshuffle'          		=> $data_slideshuffle, 
			'smallSize'          			=> $data_smallSize,  
			'mediumSize'          			=> $data_mediumSize,  
			'largeSize'          			=> $data_largeSize,  
			'thumbnailsPosition'          	=> $data_thumbnailsPosition,
			'thumbnailArrows'          		=> $data_thumbnailArrows,  
			'autoplay'          			=> $data_autoplay,  
			'autoplayDirection'          	=> $data_autoplayDirection,   
			'autoplayOnHover' 				=> $data_autoplayOnHover,	
			'post_title_show'		      	=> $data_post_title_show,
			'post_link_title'		      	=> $data_post_link_title,
			'post_excerpt_show'				=> $data_post_excerpt_show,
			'post_excerpt_characters'       => $data_post_excerpt_characters,	
			'post_excerpt_readmore'       	=> $data_post_excerpt_readmore,			
			);
			
			$update_cpspsettings = serialize($cpsp_settings_arr);			
			//update to database			
			if(update_option( CPSP_SLIDER_SLUG . '_options', $update_cpspsettings ) == true)
			{
				$message = 'Setting is updated';
			}
			
			endif;
			?>
            <script type="text/javascript">
			var q = jQuery.noConflict();
			q(document).ready(function(){			
			q( "#settingsform" ).validate({
				rules: {				
				slideAnimationDuration: {				
				digits: true,
				maxlength: 3
				},		
				smallSize: {				
				digits: true,
				maxlength: 3
				},
				mediumSize: {				
				digits: true,
				maxlength: 3
				},
				largeSize: {				
				digits: true,
				maxlength: 4,
				max: 1024
				},	
				post_excerpt_characters: {				
				digits: true,
				maxlength: 3
				},		
				},
				 messages: {                                
				 slideAnimationDuration:"Maximum length is 3, only numbers allowed",
				 smallSize:"Maximum length is 3, only numbers allowed",
				 mediumSize:"Maximum length is 3, only numbers allowed",
				 largeSize:"Maximum length is 4 digits, only numbers allowed and max value allowed is 1024",
				 post_excerpt_characters:"Maximum length is 3, only numbers allowed",
				 
				 }
                                
				});			
			});
			</script>
			<div class="wrap">
				<h2><?php _e('Category Posts Slider Pro', CPSP_SLIDER_SLUG)?></h2>
                <div class="cpsp-wrap"> 
                <div class="col-md-11">
            <h3><span class="glyphicon glyphicon-asterisk"></span><?php _e('How to Use', CPSP_SLIDER_SLUG) ?></h3>
			<div id="dashboard-widgets-container" class="cpsp-overview">
		    <div id="dashboard-widgets" class="metabox-holder">
				<div id="post-body">
					<div id="dashboard-widgets-main-content">
						<div class="postbox-container" id="main-container" style="width:75%;">
							<?php _e('Go through the steps below to create your category posts slider:', CPSP_SLIDER_SLUG) ?>
							<p>							
							<b><?php _e('Step 1', CPSP_SLIDER_SLUG) ?></b> - <?php _e('Click on Add Post Slides tab to add posts slides to particular category', CPSP_SLIDER_SLUG) ?> <a href="<?php echo admin_url('admin.php?page=category_posts_slider_pro-new') ?>"><?php _e('Here', CPSP_SLIDER_SLUG) ?></a>. <?php _e('You can add as many slides for particular category as you want', CPSP_SLIDER_SLUG) ?> .</li>							
							</p>
							<p>
							<b><?php _e('Step 2', CPSP_SLIDER_SLUG) ?></b> - <?php _e(' To add slider to your post/page you can use the shortcode [cpsp_posts_slider_pro]', CPSP_SLIDER_SLUG) ?> .</li>
							</p>
							<p>
							<b><?php _e('Step 3', CPSP_SLIDER_SLUG) ?></b> - <?php _e('You can easily perform settings for slider and customize it from the below settings section', CPSP_SLIDER_SLUG) ?> .</li>
							</p>
						</div>
			    		<div class="postbox-container" id="side-container" style="width:24%;">
						</div>						
					</div>
				</div>
		    </div>
		</div>
                
                
                <div id="icon-options-general" class="icon32"><br/></div>
                <h3><span class="glyphicon glyphicon-asterisk"></span><?php _e('Settings for the Slider', CPSP_SLIDER_SLUG)?></h3> 
                <div class="cpsp-overview">

				<?php if (!empty($message)): ?>
				<div id="message" class="updated"><p><?php echo $message ?></p></div>
				<?php endif;?>

				<form id="settingsform" method="POST">
					<table class="form-table" style="background-color:inherit;">
						<tbody>	
                        	
                             <tr>
								<th scope="row"><label><?php _e('Tab options:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<select name="tabtype">
										<option value="default" <?php echo (stripslashes($data_tab_type) == 'default') ? 'selected' : '';?>><?php _e('Horizontal', CPSP_SLIDER_SLUG)?></option>
										<option value="vertical" <?php echo (stripslashes($data_tab_type) == 'vertical') ? 'selected' : '';?>><?php _e('Vertical', CPSP_SLIDER_SLUG)?></option>
                                        <option value="accordion" <?php echo (stripslashes($data_tab_type) == 'accordion') ? 'selected' : '';?>><?php _e('Accordion', CPSP_SLIDER_SLUG)?></option>
									</select>
									<p style="font-size: 12px;"><i><?php _e('Tab type default value is horizontal', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>
							 <tr>
								<th scope="row"><label><?php _e('Active tab background:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<input type="text" name="activetab_bg" id="activetab_bg" value="<?php echo esc_attr($data_activetab_bg);?>" class="required cpsp-color-picker" data-default-color="#fff"/>
									<p style="font-size: 12px;"><i><?php _e('Font color for active tab', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>
                            
                             <tr>
								<th scope="row"><label><?php _e('Active tab font color:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<input type="text" name="activetab_fontcolor" id="activetab_fontcolor" value="<?php echo esc_attr($data_activetab_fontcolor);?>" class="required cpsp-color-picker" data-default-color="#fff"/>
									<p style="font-size: 12px;"><i><?php _e('Background color for active tab', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>
							
							 <tr>
								<th scope="row"><label><?php _e('Inactive tab background:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<input type="text" name="inactive_bg" id="inactive_bg" value="<?php echo esc_attr($data_inactive_bg);?>" class="required cpsp-color-picker" data-default-color="#EAAA91"/>
									<p style="font-size: 12px;"><i><?php _e('Background color for inactive tabs', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>
                        						
							<tr>                            
								<th scope="row"><label><?php _e('Active tab border color:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<input type="text" name="active_border_color" id="active_border_color" value="<?php echo esc_attr($data_active_border_color);?>" class="required cpsp-color-picker" data-default-color="#E84D10"/>
									<p style="font-size: 12px;"><i><?php _e('Border color for active tabs', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>
                            
                            <tr>                            
								<th scope="row"><label><?php _e('Active content border color:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<input type="text" name="active_content_border_color" id="active_content_border_color" value="<?php echo esc_attr($data_active_content_border_color);?>" class="required cpsp-color-picker" data-default-color="#D65C2C"/>
									<p style="font-size: 12px;"><i><?php _e('Choose border color for active tab content box', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>
                          
                           
                            <tr>
                            <th scope="row"><p style="text-decoration:underline; font-size:14px;"><?php _e('Slider Settings:', CPSP_SLIDER_SLUG)?></p></th>
                            </tr>
                             <tr>
								<th scope="row"><label><?php _e('Orientation:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<select name="orientation">
										<option value="horizontal" <?php echo (stripslashes($data_orientation) == 'horizontal') ? 'selected' : '';?>><?php _e('horizontal', CPSP_SLIDER_SLUG)?></option>
										<option value="vertical" <?php echo (stripslashes($data_orientation) == 'vertical') ? 'selected' : '';?>><?php _e('vertical', CPSP_SLIDER_SLUG)?></option>
									</select>
									<p style="font-size: 12px;"><i><?php _e('Indicates whether the slides will be arranged horizontally or vertically', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>                            
                            
                             <tr>
								<th scope="row"><label><?php _e('Loop:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<input name="loop" id="loop" type="checkbox" <?php if(esc_attr($data_loop) == 'true'){?> checked="checked" <?php } ?>>
									<p style="font-size: 12px;"><i><?php _e('Indicates if the slider will be loopable (infinite scrolling)', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>
                            
                             <tr>
								<th scope="row"><label><?php _e('Slide Animation Duration:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<input type="text" name="slideAnimationDuration" id="slideAnimationDuration" value="<?php echo esc_attr($data_slideAnimationDuration);?>" class="required b-width-vertical" />
									<p style="font-size: 12px;"><i><?php _e('Sets the duration of the slide animation. Default is 700', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>
							<tr>
								<th scope="row"><label><?php _e('Slide fade effect:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<input name="fade" id="fade" type="checkbox" <?php if(esc_attr($data_fade) == 'true'){?> checked="checked" <?php } ?>>
									<p style="font-size: 12px;"><i><?php _e('Indicates if fade will be used', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>
                            
                           
                            <tr>
								<th scope="row"><label><?php _e('Show Arrows:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<input name="arrows" id="arrows" type="checkbox" <?php if(esc_attr($data_arrows) == 'true'){?> checked="checked" <?php } ?>>
									<p style="font-size: 12px;"><i><?php _e('Indicates whether the arrow buttons will be created', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>
                             <tr>
								<th scope="row"><label><?php _e('Show navigation buttons:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<input name="buttons" id="buttons" type="checkbox" <?php if(esc_attr($data_buttons) == 'true'){?> checked="checked" <?php } ?>>
									<p style="font-size: 12px;"><i><?php _e('Indicates whether navigation buttons will be created', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>
                              <tr>
								<th scope="row"><label><?php _e('Keyboard Navigation:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<input name="keyboard" id="keyboard" type="checkbox" <?php if(esc_attr($data_keyboard) == 'true'){?> checked="checked" <?php } ?>>
									<p style="font-size: 12px;"><i><?php _e('Indicates whether keyboard navigation will be enabled. Default is enabled', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>
                            <tr>
								<th scope="row"><label><?php _e('Shuffle:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<input name="slideshuffle" id="slideshuffle" type="checkbox" <?php if(esc_attr($data_slideshuffle) == 'true'){?> checked="true" <?php } ?>>
									<p style="font-size: 12px;"><i><?php _e('Indicates if the slides will be shuffled', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>
                             <tr>
								<th scope="row"><label><?php _e('Image small size:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<input type="text" name="smallSize" id="smallSize" value="<?php echo esc_attr($data_smallSize);?>" class="required b-width-vertical" />
									<p style="font-size: 12px;"><i><?php _e('If the slider size is below this size, the small version of the images will be used. Default is 480', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>
                            <tr>
								<th scope="row"><label><?php _e('Image medium size:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<input type="text" name="mediumSize" id="mediumSize" value="<?php echo esc_attr($data_mediumSize);?>" class="required b-width-vertical" />
									<p style="font-size: 12px;"><i><?php _e('If the slider size is below this size, the medium version of the images will be used. Default is 768', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>
                            <tr>
								<th scope="row"><label><?php _e('Image large size:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<input type="text" name="largeSize" id="largeSize" value="<?php echo esc_attr($data_largeSize);?>" class="required b-width-vertical" />
									<p style="font-size: 12px;"><i><?php _e('If the slider size is below this size, the large version of the images will be used. Default is 1024', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>
                            <tr>
								<th scope="row"><label><?php _e('Thumbnail Position:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<select name="thumbnailsPosition">
										<option value="bottom" <?php echo (stripslashes($data_thumbnailsPosition) == 'bottom') ? 'selected' : '';?>><?php _e('Bottom', CPSP_SLIDER_SLUG)?></option>
										<option value="top" <?php echo (stripslashes($data_thumbnailsPosition) == 'top') ? 'selected' : '';?>><?php _e('Top', CPSP_SLIDER_SLUG)?></option>
                                        <option value="right" <?php echo (stripslashes($data_thumbnailsPosition) == 'right') ? 'selected' : '';?>><?php _e('Right', CPSP_SLIDER_SLUG)?></option>
                                        <option value="left" <?php echo (stripslashes($data_thumbnailsPosition) == 'left') ? 'selected' : '';?>><?php _e('Left', CPSP_SLIDER_SLUG)?></option>
									</select>
									<p style="font-size: 12px;"><i><?php _e('Sets the position of the thumbnail scroller', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>                             
                             <tr>
								<th scope="row"><label><?php _e('Thumbnail Arrows:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<input name="thumbnailArrows" id="thumbnailArrows" type="checkbox" <?php if(esc_attr($data_thumbnailArrows) == 'true'){?> checked="checked" <?php } ?>>
									<p style="font-size: 12px;"><i><?php _e('Indicates whether the thumbnail arrows will be enabled', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>  
                             <tr>
								<th scope="row"><label><?php _e('Autoplay:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<input name="autoplay" id="autoplay" type="checkbox" <?php if(esc_attr($data_autoplay) == 'true'){?> checked="checked" <?php } ?>>
									<p style="font-size: 12px;"><i><?php _e('Indicates whether or not autoplay will be enabled', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>
                              <tr>
								<th scope="row"><label><?php _e('Autoplay Direction:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<select name="autoplayDirection">
										<option value="normal" <?php echo (stripslashes($data_autoplayDirection) == 'normal') ? 'selected' : '';?>><?php _e('Normal', CPSP_SLIDER_SLUG)?></option>
										<option value="backwards" <?php echo (stripslashes($data_autoplayDirection) == 'backwards') ? 'selected' : '';?>><?php _e('Backwards', CPSP_SLIDER_SLUG)?></option>                                       
									</select>
									<p style="font-size: 12px;"><i><?php _e('Indicates whether autoplay will navigate to the next slide or previous slide', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr> 
                            <tr>
								<th scope="row"><label><?php _e('Autoplay Onhover:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<select name="autoplayOnHover">
										<option value="pause" <?php echo (stripslashes($data_autoplayOnHover) == 'pause') ? 'selected' : '';?>><?php _e('Pause', CPSP_SLIDER_SLUG)?></option>
										<option value="stop" <?php echo (stripslashes($data_autoplayOnHover) == 'stop') ? 'selected' : '';?>><?php _e('Stop', CPSP_SLIDER_SLUG)?></option>                     
                                        <option value="none" <?php echo (stripslashes($data_autoplayOnHover) == 'none') ? 'selected' : '';?>><?php _e('None', CPSP_SLIDER_SLUG)?></option>                                       
									</select>
									<p style="font-size: 12px;"><i><?php _e('Indicates if the autoplay will be paused or stopped when the slider is hovered', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>
                             <tr>
								<th scope="row"><label><?php _e('Display post title:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<input name="post_title_show" id="post_title_show" type="checkbox" <?php if(esc_attr($data_post_title_show) == 'true'){?> checked="checked" <?php } ?>>
									<p style="font-size: 12px;"><i><?php _e('Indicates whether or not to display post title on the slide', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr> 
                             <tr>
								<th scope="row"><label><?php _e('Post link on title:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<input name="post_link_title" id="post_link_title" type="checkbox" <?php if(esc_attr($data_post_link_title) == 'true'){?> checked="checked" <?php } ?>>
									<p style="font-size: 12px;"><i><?php _e('Indicates whether or not to display post link on title', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>  
                             <tr>
								<th scope="row"><label><?php _e('Display post excerpt:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<input name="post_excerpt_show" id="post_excerpt_show" type="checkbox" <?php if(esc_attr($data_post_excerpt_show) == 'true'){?> checked="checked" <?php } ?>>
									<p style="font-size: 12px;"><i><?php _e('Indicates whether or not to display post excerpt', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr> 
                             <tr>
								<th scope="row"><label><?php _e('Post excerpt characters limit:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
                                <input type="text" name="post_excerpt_characters" id="post_excerpt_characters" value="<?php echo esc_attr($data_post_excerpt_characters);?>" class="required b-width-vertical" />									
									<p style="font-size: 12px;"><i><?php _e('Enter numeric value for post excerpt characters limit. Default is 100', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>  
                             <tr>
								<th scope="row"><label><?php _e('Post excerpt read more link:', CPSP_SLIDER_SLUG)?></label></th>
								<td>
									<input name="post_excerpt_readmore" id="post_excerpt_readmore" type="checkbox" <?php if(esc_attr($data_post_excerpt_readmore) == 'true'){?> checked="checked" <?php } ?>>
									<p style="font-size: 12px;"><i><?php _e('Indicates whether or not to display read more link on post excerpt', CPSP_SLIDER_SLUG)?></i></p>
								</td>
							</tr>
						</tbody>
					</table>
					<?php wp_nonce_field( 'cpsp-setting-save-nonce', 'cpsp-setting-nonce' );?>
					<p><input type="submit" value="<?php _e('Save', CPSP_SLIDER_SLUG)?>" id="submit" class="button-primary" name="submit"></p>

				</form>
			</div>
		<?php
		}
	}
?>