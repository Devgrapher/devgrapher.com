<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
} 
function cpsp_posts_slider_display($atts, $content=null){
 
 global $wpdb;

/******************************** Display for Slider Starts ******************************/
 $cpsp_data = LoadCpspOptionsData();
 //print_r($cpsp_data);
 $data_table = $wpdb->prefix . "cpsp_slides"; 
 $blocks_data = $wpdb->get_results("SELECT * FROM ".$data_table." ", OBJECT);	
 //print_r($blocks_data);
 $cpsp_display_slide = "";	

 if( !empty($blocks_data) )
 {	 	
	
	/*******************Category Tabs starts here*****************************/
	$cpsp_display_slide .= '<div id="cpspCategoryTabs">
			<ul class="resp-tabs-list hor_1">';
			$m=0;
			foreach ($blocks_data as $blocks_val)
			{
				$category = get_cat_name( $blocks_val->category_id );	
				$cpsp_display_slide .= '<li id="'.$m.'" data-catid="'.$blocks_val->category_id.'">'.$category.'</li>';
				$m++;
			}			
			$first_slide = $blocks_data[0]->category_id;	
			$cpsp_display_slide .= '</ul>			
			<div class="resp-tabs-container hor_1">';	
			
                        $p=0;
                        
                        foreach ($blocks_data as $blocks_val){
						    $tabs_data = array();		
							$tabs_data = explode(',',$blocks_val->posts_id);						
                           $category = get_cat_name( $blocks_val->category_id );
                           $cpsp_display_slide .= '<div id="cpsp-tabs-slide'.$p.'" class="slide-banner" data-contentid='.$p.' data-catid="'.$blocks_val->category_id.'">
                                                <div id="cpsp_sliderpro" class="slider-pro">
                                                <div class="sp-slides">'; 
                           foreach($tabs_data as $tabs_val){
							   /****************************** Get Posts information ****************************/
							   	$queried_post = get_post($tabs_val);
							   	$title = $queried_post->post_title;
								$permlink = "";
							  	$posts_image_src =  wp_get_attachment_url( get_post_thumbnail_id($tabs_val) );							  
							   /********************************************************************************/
							    if($posts_image_src == "")
							   {
								  $posts_image_src = CPSP_URL.'images/default-image.jpg';
							   }
							   //print_r($tabs_val);
							   
                               $cpsp_display_slide .= '<div class="sp-slide">';
                               $cpsp_display_slide .= '<img class="sp-image" src="'.esc_url($posts_image_src).'" 
                                                data-src="'.esc_url($posts_image_src).'"
                                                data-small="'.esc_url($posts_image_src).'"
                                                data-medium="'.esc_url($posts_image_src).'"
                                                data-large="'.esc_url($posts_image_src).'"
                                                data-retina="'.esc_url($posts_image_src).'"/>';
												
												if($cpsp_data->post_title_show == "true")
												{
													if($cpsp_data->post_link_title == "true")
														{
														$permlink = get_permalink($tabs_val);
														$cpsp_display_slide .='<a href="'.$permlink.'"><h3 class="sp-layer sp-black sp-padding" data-horizontal="5%" data-vertical="20%" data-show-transition="up" data-show-delay="200" data-position="topLeft">'.esc_attr($title).'</h3></a>';				
														}
														else
														{
															$cpsp_display_slide .='<h3 class="sp-layer sp-black sp-padding" data-horizontal="5%" data-vertical="20%" 
														data-show-transition="up" data-show-delay="200" data-position="topLeft">'.esc_attr($title).'</h3>';	
														}													
												}
												if($cpsp_data->post_excerpt_show == "true")
												{
													$exc_content = cpsp_customize_excerpt($tabs_val,$cpsp_data->post_excerpt_characters);
													if($cpsp_data->post_excerpt_readmore == "true")
													{
														$permlink = get_permalink($tabs_val);
														$cpsp_display_slide .= '<p class="sp-layer sp-white sp-padding" data-horizontal="5%" data-vertical="5%" data-show-transition="left" data-show-delay="500" data-width="60%" data-position="bottomLeft">'.esc_attr($exc_content).'<a href="'.$permlink.'">Read More</a></p>';
													}
													else
													{
														$cpsp_display_slide .= '<p class="sp-layer sp-white sp-padding" data-horizontal="5%" data-vertical="5%" data-show-transition="left" data-show-delay="500" data-width="60%" data-position="bottomLeft">'.esc_attr($exc_content).'</p>';
													}	
												}
                              
                               $cpsp_display_slide .= '</div>';
                           }//tabs data slides images
                           
                           $cpsp_display_slide .= '</div>'; //sp slides
                           
                           $cpsp_display_slide .= '<div class="sp-thumbnails">';
                           foreach($tabs_data as $tabs_val){
							   $pthumnailImage = wp_get_attachment_image_src( get_post_thumbnail_id($tabs_val), 'thumbnail' );
							    if($pthumnailImage == "")
							   {
								  $pthumnailImage[0] = CPSP_URL.'images/default-thumbnail.png';
							   }
                               $cpsp_display_slide .= '<img class="sp-thumbnail" src="'.esc_url($pthumnailImage[0]).'"/>';
                           }                           
                           $cpsp_display_slide .= '</div>'; //sp thumbnails ends
                           $cpsp_display_slide .= '</div>'; 
                           $cpsp_display_slide .= '</div>';
                           $p++;
                        }
                       
			$cpsp_display_slide .= '</div>'; //resp-tabs-container
                        $cpsp_display_slide .= '</div>'; //cpspCategoryTabs div	
 }
 else{
	 $cpsp_display_slide .= esc_html('Please add posts slides for the categories from plugin settings.');
 }
 return $cpsp_display_slide;
}
?>