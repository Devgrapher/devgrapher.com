<?php 
/**
Plugin Name: Category Posts Slider Pro
Plugin URI: https://neelamsamariya.wordpress.com/
Description: Fully Responsive and Mobile Friendly way to display category posts slider.
Author: Neelam Samariya
Version: 1.1
Text Domain: category-posts-slider-pro 
Author URI: https://neelamsamariya.wordpress.com/
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
 
$cpsp_siteurl = get_option('siteurl');
if ( ! defined( 'CPSP_FOLDER' ) ) {
				define( 'CPSP_FOLDER', basename( dirname( __FILE__ ) ) );
}
if ( ! defined( 'CPSP_DIR' ) ) {
	define( 'CPSP_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'CPSP_URL' ) ) {
	define( 'CPSP_URL', plugin_dir_url( CPSP_FOLDER ).CPSP_FOLDER.'/' );
}


global $wpdb;
define('CPSP_SLIDER_SLUG','category_posts_slider_pro');
/**
	 * Current version of the Category Posts Slider Pro plugin.
*/
const VERSION = '1.0';
function cpsp_activation() {
	global $wpdb;
	
	$cpsp_slides_table = $wpdb->prefix . 'cpsp_slides';	
	$charset_collate = $wpdb->get_charset_collate();
	if($wpdb->get_var("SHOW TABLES LIKE '$cpsp_slides_table'") != $cpsp_slides_table){
	// sql to create slides table for plugin
	$slidestbl_query = "CREATE TABLE $cpsp_slides_table (
			id int(20) NOT NULL AUTO_INCREMENT,		
			category_id int(10) NOT NULL,
			posts_id text,				
			PRIMARY KEY id (id)
			) $charset_collate;";
	}	
	$cpsp_settings = array();
	$cpsc_defaults = array(                  
            'tab_type'            			=> 'default',
            'activetab_bg'        			=> '#EAAA91',
			'activetab_fontcolor'        	=> '#fff',
            'inactive_bg'         			=> '#fff',			
            'active_border_color' 			=> '#E84D10',            
            'active_content_border_color'  	=> '#D65C2C',          
            'orientation' 					=> 'horizontal',
			'loop' 							=> 'true',
			'slideAnimationDuration' 		=> 700,			       
            'fade'     						=> 'false',            
            'arrows'          				=> 'false',
            'buttons'           			=> 'true',
			'keyboard'           			=> 'true',
            'slideshuffle'          		=> 'false', 
			'smallSize'          			=> 480,  
			'mediumSize'          			=> 768,  
			'largeSize'          			=> 1024,  
			'thumbnailsPosition'          	=> 'bottom',			
			'thumbnailArrows'          		=> 'false',  
			'autoplay'          			=> 'true',  
			'autoplayDirection'          	=> 'normal',   
			'autoplayOnHover' 				=> 'pause',	
			'post_title_show'		      	=> 'true',
			'post_link_title'		      	=> 'true',
			'post_excerpt_show'				=> 'true',
			'post_excerpt_characters'       => 100,
			'post_excerpt_readmore'       => 'false',
            
        );
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $slidestbl_query );
	add_option( 'cpsp_db_version', VERSION );	
	$cpsp_settings = serialize($cpsc_defaults);
	add_option(CPSP_SLIDER_SLUG . '_options', $cpsp_settings);
	
	
}


 /**
 * deactivate the plugin
 */
function cpsp_deactivation() {
       	global $wpdb;
	    if ( !current_user_can( 'activate_plugins' ) ) {
           return;
        }
        delete_option( CPSP_SLIDER_SLUG . '_options' );
		delete_option( 'cpsp_db_version' );	
		
		$cpsp_tblname = $wpdb->prefix . "cpsp_slides";
		$wpdb->query("DROP TABLE IF EXISTS $cpsp_tblname");
}

register_activation_hook(__FILE__,'cpsp_activation');
register_deactivation_hook(__FILE__ , 'cpsp_deactivation' );

/* Add category posts slider in Admin Menu Item*/
add_action('admin_menu','cpsp_admin_menu_options');
/**
 * This function used to setup admin menu.
 * @author Neelam Code
 * @version 1.0
 * @package Category Posts Slider Pro
 */
function cpsp_admin_menu_options(){
		
	$pagehook1 = add_menu_page(
			'Category Posts Slider Pro',
			'Category Posts Slider Pro',
			'manage_options',
			CPSP_SLIDER_SLUG,
			'cpsp_settings',
			plugins_url( 'images/slideshow_icon.png', __FILE__ )			
		);
		
		$pagehook2 = add_submenu_page(
			CPSP_SLIDER_SLUG,
			__( 'Plugin Settings', CPSP_SLIDER_SLUG ),
			__( 'Plugin Settings', CPSP_SLIDER_SLUG ),
			'manage_options',
			CPSP_SLIDER_SLUG,
			'cpsp_settings'
		);
		
		$pagehook3 = add_submenu_page(
			CPSP_SLIDER_SLUG,
			__( 'Add New Slider', CPSP_SLIDER_SLUG ),
			__( 'Add New', CPSP_SLIDER_SLUG ),
			'manage_options',
			CPSP_SLIDER_SLUG . '-new',
			'cpsp_addnew_slides'
		);
		
		

	add_action('load-'.$pagehook1, 'cpsp_wp_head');
	add_action('load-'.$pagehook2, 'cpsp_wp_head');	
	add_action('load-'.$pagehook3, 'cpsp_wp_head');
		
}

/**
 * This function used to load scripts and styles for admin.
 * @author Neelam Code
 * @version 1.0
 * @package Category Posts Slider Pro
 */
function cpsp_wp_head()
{	
	
	wp_enqueue_style('cpsp_slider_css',plugins_url( 'css/bootstrap.css' , __FILE__ ));
	// Css rules for Color Picker		
    wp_enqueue_style( 'wp-color-picker' );		
	wp_register_script("cpsp_category_posts_js", plugin_dir_url(__FILE__) . "js/category_posts_js.js", array( 'jquery', 'wp-color-picker' ), '1.0.0', true);
	$cpspadminParameters = array(
	 	'cpsp_slider_nonce' => wp_create_nonce( "cpsp-saveslides-nonce" ),
		'ajaxurl' => admin_url('admin-ajax.php'));
	
	wp_localize_script( 'cpsp_category_posts_js', 'cpsp_ajax', $cpspadminParameters);	     
	wp_enqueue_script( 'jquery' );
    wp_enqueue_script("cpsp_category_posts_js");
	wp_enqueue_script('cpsp-jquery-validation-plugin', plugins_url('js/jquery.validate.min.js', __FILE__ ), array('jquery'), '', true);
    
}


/*
 * registers frontend scripts and styles
 */
add_action( "wp_enqueue_scripts", "cpsp_register_scripts");
function cpsp_register_scripts() {
 wp_enqueue_style('cpsp-responsive-tabs', plugin_dir_url( __FILE__ ). 'css/cpsp-responsive-tabs.css');	
 wp_enqueue_style('cpsp-slider-display', plugin_dir_url( __FILE__ ). 'css/cpsp-slider-display.min.css');
 wp_enqueue_script('jquery');
 wp_enqueue_script( 'cpsp-ResponsiveTabs-js', plugin_dir_url( __FILE__ ) . 'js/cpspResponsiveTabs.js', array('jquery'), '', true ); 	
 wp_enqueue_script('cpsp-frontend-slider-min', plugin_dir_url( __FILE__ ) . 'js/cpsp-frontend-slider.min.js', array('jquery'), '', true );	 
 wp_enqueue_script( 'cpsp-frontend-js', plugin_dir_url( __FILE__ ) . '/js/category-posts-frontend.js', array('jquery'), '1.0.0', true );
 
 /************************** Data to be passed to js file ******************************************/
 $cpsp_data = LoadCpspOptionsData();
 //print_r($cpsp_data);

 $cpspParameters = array(
	'ajax_nonce' 		=> wp_create_nonce( "cpsp-frontend-nonce" ),
	'ajaxUrl' 		=> admin_url('admin-ajax.php'),
	'tabtype'    		=> $cpsp_data->tab_type,
	'activetab_bg' => $cpsp_data->activetab_bg,
	'activetab_fontcolor' => $cpsp_data->activetab_fontcolor,
	'inactive_bg' => $cpsp_data->inactive_bg,		
	'active_border_color' => $cpsp_data->active_border_color,
	'active_content_border_color' => $cpsp_data->active_content_border_color,
	'orientation' => $cpsp_data->orientation,
	'loop' => $cpsp_data->loop,
	'thumbnailsPosition' => $cpsp_data->thumbnailsPosition,
	'fade' =>  $cpsp_data->fade,
	'arrows' => $cpsp_data->arrows,
	'buttons' => $cpsp_data->buttons,
	'keyboard' => $cpsp_data->keyboard,
	'slideshuffle' => $cpsp_data->slideshuffle,
	'smallSize' => $cpsp_data->smallSize,
	'mediumSize' => $cpsp_data->mediumSize,
	'largeSize' => $cpsp_data->largeSize,
	'thumbnailArrows' => $cpsp_data->thumbnailArrows,
	'autoplay' => $cpsp_data->autoplay,
	'autoplayDirection' => $cpsp_data->autoplayDirection,
	'autoplayOnHover' => $cpsp_data->autoplayOnHover,
	'slideAnimationDuration' => $cpsp_data->slideAnimationDuration,
);
//print_r($cpspParameters);
 
 wp_localize_script( 'cpsp-frontend-js', 'CpspAjax', $cpspParameters);	 
}	

/**
 * This function used to call settings page.
 * @author Neelam Code
 * @version 1.0
 * @package Category Posts Slider Pro
 */
function cpsp_settings()
{
	 include CPSP_DIR.'/cpsp-settings.php';
	 cpsp_load_settings();
}
/**
 * This function used to load default slider options.
 * @author Neelam Code
 * @version 1.0
 * @package Category Posts Slider Pro
 */
function cpsp_loadSliderDefaults()
{
	$cpsc_defaults = array();
	$cpsc_defaults = array(                  
            'tab_type'            			=> 'default',
            'activetab_bg'        			=> '#EAAA91',
			'activetab_fontcolor'			=> '#fff',
            'inactive_bg'         			=> '#fff',			
            'active_border_color' 			=> '#E84D10',            
            'active_content_border_color'  	=> '#D65C2C',          
            'orientation' 					=> 'horizontal',
			'loop' 							=> 'true',
			'slideAnimationDuration' 		=> 700,			       
            'fade'     						=> 'false',            
            'arrows'          				=> 'false',
            'buttons'           			=> 'true',
			'keyboard'           			=> 'true',
            'slideshuffle'          		=> 'false', 
			'smallSize'          			=> 480,  
			'mediumSize'          			=> 768,  
			'largeSize'          			=> 1024,  
			'thumbnailsPosition'          	=> 'bottom',
			'thumbnailArrows'          		=> 'false',  
			'autoplay'          			=> 'true',  
			'autoplayDirection'          	=> 'normal',   
			'autoplayOnHover' 				=> 'pause',	
			'post_title_show'		      	=> 'true',
			'post_link_title'		      	=> 'true',
			'post_excerpt_show'				=> 'true',
			'post_excerpt_characters'       => 100,
			'post_excerpt_readmore'       => 'false',
            
        );
		return (object)$cpsc_defaults;
}
/**
 * This function used to call add posts slides according to the category.
 * @author Neelam Code
 * @version 1.0
 * @package Category Posts Slider Pro
 */
 
function cpsp_addnew_slides()
{
	 include CPSP_DIR.'/cpsp-add-slides.php';
}
/**
 * This function used to show success/failure message in backend.
 * @author Neelam Code
 * @version 1.0
 * @package Category Posts Slider Pro
 */
function cpsp_slider_showMessage($message, $errormsg = false)
{
	if( empty($message) )
	return;
	
	if ( $errormsg ) {
		echo '<div id="message" class="error">';
	}
	else {
		echo '<div id="message" class="updated">';
	}
	echo "<p><strong>$message</strong></p></div>";
}

/**
 * This function used to create categories dropdown.
 * @author Neelam Code
 * @version 1.0
 * @package Category Posts Slider Pro
 */
function cpsp_load_section_Categories()
{
	 $cpsp_args = array(
			'type'                     => 'post',
			'child_of'                 => 0,
			'parent'                   => '',
			'orderby'                  => 'name',
			'order'                    => 'ASC',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'exclude'                  => '',
			'include'                  => '',
			'number'                   => '',
			'taxonomy'                 => 'category',
			'pad_counts'               => false 

); 
$cpsp_categories_data = get_categories($cpsp_args);
return $cpsp_categories_data;
}

////////////////////////////////////////////////////////////////////////////
add_action("wp_ajax_cpsp_loadCategories", "cpsp_loadCategories");
add_action("wp_ajax_nopriv_cpsp_loadCategories", "invalid_cpsp_login");

/**
 * This function used to create categories dropdown through ajax.
 * @author Neelam Code
 * @version 1.0
 * @package Category Posts Slider Pro
 */
function cpsp_loadCategories() {

   if ( !wp_verify_nonce( $_REQUEST['nonce'], "cpsp-ajax-nonce")) {
      exit("Sorry security issue");
   }  
   
   if((isset($_REQUEST['counter_id'])) && ($_REQUEST['counter_id'] > 0))
   {
	  
	   $cpsp_args = array(
			'type'                     => 'post',
			'child_of'                 => 0,
			'parent'                   => '',
			'orderby'                  => 'name',
			'order'                    => 'ASC',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'exclude'                  => '',
			'include'                  => '',
			'number'                   => '',
			'taxonomy'                 => 'category',
			'pad_counts'               => false 

); 
	$cpsp_cntr = intval($_REQUEST['counter_id']);
	$categories_data = get_categories($cpsp_args);
	echo '<select name="category_id'.$cpsp_cntr.'" id= "category_id'.$cpsp_cntr.'" data-catid="'.$cpsp_cntr.'" class="form-control pcategories">
                <option value="0">Select Category</option>';
               
                if(count($categories_data) > 0){
                    foreach($categories_data as $catvalues)
                    {
                        echo '<option value="'.$catvalues->cat_ID.'">'.$catvalues->cat_name.'</option>';
                    }
                }
               
    echo '</select>';
   }
   

   die();

}

function invalid_cpsp_login() {
   echo "You must log in to add the slides";
   die();
}

/////////////////////////////////////////////////////////////////////////////

add_action("wp_ajax_cpsp_LoadPosts", "cpsp_LoadPosts");
add_action('wp_ajax_nopriv_cpsp_LoadPosts', 'cpsp_LoadPosts');
/**
 * This function used to create posts selectbox via ajax.
 * @author Neelam Code
 * @version 1.0
 * @package Category Posts Slider Pro
 */
function cpsp_LoadPosts()
{
	global $wpdb;
		
	if(!check_ajax_referer( 'cpsp-ajax-nonce', 'nonce', false ))
	{
		echo 'Sorry security issue';
		exit();
	}		
	if ( ! current_user_can( 'manage_options') ) {
		return;
	}
	$sel_catid = intval($_POST['category']);
	
	if(($sel_catid != "") && ($sel_catid != 0))
	{			
		$cpsppost_array = cpsp_getAllPostsByCategory($sel_catid);		
		//print_r($post_array);
		if(count((array)$cpsppost_array))			
		{
			
			foreach ($cpsppost_array as $cpspoptionvalues)
			{
				echo '<option value="'.$cpspoptionvalues->id .'">'. $cpspoptionvalues->title .'</option>';	
			}//for loop end
		}
		else{
			echo '0';
		}
		
	}
	die();
		
}

/**
 * This function used to get all posts of a particular category id.
 * @author Neelam Code
 * @version 1.0
 * @package Category Posts Slider Pro
 */
function cpsp_getAllPostsByCategory($categoryid) {
		
		$PostsArray = new stdClass;
		$sid = 0;
		$cpspargs = array( 'cat' => $categoryid, 'post_status' => 'publish', 'orderby' => 'post_date', 'order' => 'DESC');
		
		$cpsp_post_loop = new WP_Query( $cpspargs );
		while ( $cpsp_post_loop->have_posts() ) : $cpsp_post_loop->the_post();
			$PostsArray->$sid->id = get_the_ID();
			$PostsArray->$sid->title = get_the_title(get_the_ID());
			$sid++;
		endwhile;		
		//$postcolumnobject = (object) $typesPostsArray;		
        return $PostsArray;
}


add_action("wp_ajax_cpspRemoveSlideSection", "cpspRemoveSlideSection");
add_action('wp_ajax_nopriv_cpspRemoveSlideSection', 'cpspRemoveSlideSection');
/**
 * This function used to remove particular catgeory tab section from slider via admin.
 * @author Neelam Code
 * @version 1.0
 * @package Category Posts Slider Pro
 */
function cpspRemoveSlideSection() {
	global $wpdb;
	
   	if ( !wp_verify_nonce( $_REQUEST['nonce'], "cpsp-ajax-nonce")) {
      exit("Sorry security issue");
   	}
	if ( ! current_user_can( 'manage_options') ) {
		return;
	}
	$cpspid = ""; 
	if(isset($_REQUEST['id']))
	{
		$cpspid = intval($_REQUEST['id']);
	}
	
	if($cpspid == "")
	{
		echo 'empty-slide';
	}
	else{
		$slidecatid = intval($_REQUEST['category']);		
		/********************** delete category from table ********************/	
		$wpdb->query( $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "cpsp_slides WHERE category_id = %d", $slidecatid ) );
		echo 'Removed';			
		
		
	}
   die();
}

/**
 * This is a shortcode function through which slider loads in frontend.
 * @author Neelam Code
 * @version 1.0
 * @package Category Posts Slider Pro
 */
include_once("cpsp-slider-display.php");
function cpsp_posts_slider_actions()
{
add_shortcode('cpsp_posts_slider_pro','cpsp_posts_slider_display');
}
add_action('init', 'cpsp_posts_slider_actions');

/**
 * This function used to load tabs content for frontend via ajax.
 * @author Neelam Code
 * @version 1.0
 * @package Category Posts Slider Pro
 */
add_action("wp_ajax_cpsp_load_tabcontent", "cpsp_load_tabcontent");
add_action("wp_ajax_nopriv_cpsp_load_tabcontent", "cpsp_load_tabcontent"); 

function cpsp_load_tabcontent() {
	global $wpdb;
	
	if(!check_ajax_referer( 'cpsp-frontend-nonce', 'nonce', false ))
	{
		echo 'Sorry security issue';
		exit();
	}		
		
	$cpsp_data = LoadCpspOptionsData();
	$cpsp_table_name = $wpdb->prefix . "cpsp_slides";	
	$id = intval($_REQUEST['id']);
	$slidecatid = intval($_REQUEST['category']);	
	$slide_cat_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $cpsp_table_name WHERE category_id = %d", $slidecatid ));							
	$cpsp_tabsdata = array();		
	$cpsp_tabsdata = explode(',',$slide_cat_data->posts_id);
	$cpsp_display_tab = "";
	$cpsp_display_tab .= '<div id="cpsp_sliderpro" class="slider-pro">
                                                <div class="sp-slides">'; 
                           foreach($cpsp_tabsdata as $tabs_val){
							   $queried_post = get_post($tabs_val);
							   $title = $queried_post->post_title;
							   $permlink = "";
							   $posts_image_src =  wp_get_attachment_url( get_post_thumbnail_id($tabs_val) );	
							   if($posts_image_src == "")
							   {
								  $posts_image_src = CPSP_URL.'images/default-image.jpg';
							   }
                               $cpsp_display_tab .= '<div class="sp-slide">';
                               $cpsp_display_tab .= '<img class="sp-image" src="'.esc_url($posts_image_src).'" 
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
									$cpsp_display_tab .='<a href="'.$permlink.'"><h3 class="sp-layer sp-black sp-padding" data-horizontal="5%" data-vertical="20%" data-show-transition="up" data-show-delay="200" data-position="topLeft">'.esc_attr($title).'</h3></a>';				
									}
									else
									{
										$cpsp_display_tab .='<h3 class="sp-layer sp-black sp-padding" data-horizontal="5%" data-vertical="20%" 
									data-show-transition="up" data-show-delay="200" data-position="topLeft">'.esc_attr($title).'</h3>';	
									}
									
																					
								}
								
								if($cpsp_data->post_excerpt_show == "true")
								{
									$exc_content = cpsp_customize_excerpt($tabs_val,$cpsp_data->post_excerpt_characters);
									if($cpsp_data->post_excerpt_readmore == "true")
									{
										$permlink = get_permalink($tabs_val);
										$cpsp_display_tab .= '<p class="sp-layer sp-white sp-padding" data-horizontal="5%" data-vertical="5%" data-show-transition="left" data-show-delay="500" data-width="60%" data-position="bottomLeft">'.esc_attr($exc_content).'<a href="'.$permlink.'">Read More</a></p>';
									}
									else
									{
										$cpsp_display_tab .= '<p class="sp-layer sp-white sp-padding" data-horizontal="5%" data-vertical="5%" data-show-transition="left" data-show-delay="500" data-width="60%" data-position="bottomLeft">'.esc_attr($exc_content).'</p>';
									}									
									 
								}
                              
                               $cpsp_display_tab .= '</div>';
                           }//tabs data slides images
                           
                           $cpsp_display_tab .= '</div>'; //sp slides
                           
                           $cpsp_display_tab .= '<div class="sp-thumbnails">';
                           foreach($cpsp_tabsdata as $tabs_val){
							   $pthumnailImage = wp_get_attachment_image_src( get_post_thumbnail_id($tabs_val), 'thumbnail' );
							   if($pthumnailImage == "")
							   {
								  $pthumnailImage[0] = CPSP_URL.'images/default-thumbnail.png';
							   }
                               $cpsp_display_tab .= '<img class="sp-thumbnail" src="'.esc_url($pthumnailImage[0]).'"/>';
                           }                           
                           $cpsp_display_tab .= '</div>'; //sp thumbnails ends
                           $cpsp_display_tab .= '</div>';
		
		
echo $cpsp_display_tab;	
die();
}

/**
 * This function is used to return excerpt from posts content.
 * @author Neelam Code
 * @version 1.0
 * @package Category Posts Slider Pro
 */
function cpsp_customize_excerpt($post_id, $plen)
{
	$the_post = get_post($post_id); //Gets post ID
	$the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt
	$excerpt_length = $plen; //Sets excerpt length by word count
	$the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
	$words = explode(' ', $the_excerpt, $excerpt_length + 1);	
	if(count($words) > $excerpt_length) :
		array_pop($words);
		array_push($words, '…');
		$the_excerpt = implode(' ', $words);
	endif;	
	$the_excerpt = $the_excerpt;	
	
	return $the_excerpt;
}
/**
 * This function is used to load options from wp-options.
 * @author Neelam Code
 * @version 1.0
 * @package Category Posts Slider Pro
 */
function LoadCpspOptionsData()
{
	$cpsp_plugin_data = get_option( CPSP_SLIDER_SLUG.'_options' );
	$cpsp_defaults = "";
	if(!empty($cpsp_plugin_data))
	{
		$cpsp_defaults = unserialize($cpsp_plugin_data);
		
		$cpsp_dataoptions = (object) $cpsp_defaults;
		
	}	
	else
	{
		$cpsp_dataoptions = cpsp_loadSliderDefaults();
	}
	return $cpsp_dataoptions;
}

/**
 * This function is used to save category id and posts id in the database for slides.
 * @author Neelam Code
 * @version 1.0
 * @package Category Posts Slider Pro
 */
add_action("wp_ajax_cpsp_save_slides", "cpsp_save_slides");
add_action("wp_ajax_nopriv_cpsp_save_slides", "cpsp_save_slides");

function cpsp_save_slides() {
	
	$nonce = stripslashes($_POST['nonce']);	
	
	if ( ! wp_verify_nonce( $nonce, 'cpsp-saveslides-nonce' ) ) {
			die( 'Sorry security issue' );
		}
	global $wpdb;
	$cpsp_table = $wpdb->prefix ."cpsp_slides";
	$cpspslider_data = array();
	parse_str(stripslashes($_POST['SubmittedData']), $cpspslider_data);
	//print_r($cpspslider_data);
	
	$cpsp_rows = 0;
	if((isset($cpspslider_data['num_tab_sections'])) && ($cpspslider_data['num_tab_sections'] > 0))
	{
		$cpsp_rows = intval($cpspslider_data['num_tab_sections']);
		$err_slides = array();
		$cpsp_insert= array();
		for($p = 1; $p <= $cpsp_rows; $p++)
		{
			$sec_posts = "";
			$sec_category = "";
			$create_section_data = array();
			$posts_implode = "";
			$get_slidePosts = "";
			$cpsp_cat_id = "";
			if((isset($cpspslider_data['category_id'.$p])) && ($cpspslider_data['category_id'.$p] > 0))
			{
				//get catgeory name
				$sec_category = get_cat_name($cpspslider_data['category_id'.$p]);
				$cpsp_cat_id = intval($cpspslider_data['category_id'.$p]);
				//check if category has any posts selected
				if((isset($cpspslider_data['slidepost_'.$p])) && (count($cpspslider_data['slidepost_'.$p]) > 0))
				{
					
							/******************************** Get posts from table if available for this category *********************************/
							
							$get_slidePosts = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $cpsp_table WHERE category_id = %d", $cpsp_cat_id ), ARRAY_A );
							
							if(!empty($get_slidePosts))							
							{
								//update posts for category ids in table
								$posts_implode = implode(',',$cpspslider_data['slidepost_'.$p]);							
								
								$execut_query = $wpdb->query($wpdb->prepare("UPDATE $cpsp_table SET posts_id = %s where category_id = %d",$posts_implode, $cpsp_cat_id) // $wpdb->prepare
							); // $wpdb->query
								$cpsp_insert[] = $sec_category;
							}
							else
							{								
								//insert new row for the category id in table
								$posts_implode = implode(',',$cpspslider_data['slidepost_'.$p]);
								
								
								$wpdb->query( $wpdb->prepare("INSERT INTO $cpsp_table (category_id, posts_id) VALUES ( %d, %s)", array($cpsp_cat_id,$posts_implode)));								
								
								$cpsp_insert[] = $sec_category;
							}	
				}
				else
				{
					//wordpress to check if the category has any posts or not					
					$sec_posts = get_posts('category='.$cpsp_cat_id);
					if(count($sec_posts) > 0)
					{						
						$err_slides[] = $sec_category;
					}					
				}//else posts end				
			}// if category ends			
		}// for num tab sections end	
		$cpsp_inserted = "";
		$cpsp_errors = "";
		$cpsp_msg = "";
		if(count($cpsp_insert) > 0)
		{
			//$cpsp_inserted = implode(',',$cpsp_insert);
			$cpsp_msg = 'Slides Saved';
		}
		/*if(count($err_slides) > 0)
		{
			$cpsp_errors = implode(',',$err_slides);
			$cpsp_msg .= '<br/> You dint select posts for '.$cpsp_errors;
		}*/
		//print_r($cpsp_return);
		echo json_encode(array('message' => $cpsp_msg));
		
			
	}// if tab sections ends
		
	die();
	
}
?>