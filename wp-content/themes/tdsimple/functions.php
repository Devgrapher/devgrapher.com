<?php
/**
 * tdsimple functions and definitions
 *
 * @package tdsimple
 * @since tdsimple 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since tdsimple 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( 'tdsimple_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since tdsimple 1.0
 */
function tdsimple_setup() {

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	require( get_template_directory() . '/inc/extras.php' );
	
	/**
	 * Customizer additions
	 */
	require( get_template_directory() . '/inc/customizer.php' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'tdsimple' ),
	) );

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support( 'post-formats', array( 'audio', 'gallery', 'image', 'video', 'quote', 'link' ) );
}
endif; // tdsimple_setup
add_action( 'after_setup_theme', 'tdsimple_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since tdsimple 1.0
 */
function tdsimple_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Main Widget', 'tdsimple' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );
}
add_action( 'widgets_init', 'tdsimple_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function tdsimple_scripts() {
	wp_enqueue_style( 'googlefonts', '//fonts.googleapis.com/css?family=Merriweather:300,400,700' );
	wp_enqueue_style( 'slider', get_template_directory_uri() . '/css/refineslide.css' );
	wp_enqueue_style( 'icons-ie', get_template_directory_uri() . '/css/general_foundicons_ie7.css' );
	wp_enqueue_style( 'icons', get_template_directory_uri() . '/css/general_foundicons.css' );
	wp_enqueue_style( 'foundation', get_template_directory_uri() . '/css/foundation.min.css' );
	wp_enqueue_style( 'style', get_stylesheet_uri() );

	wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120206', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}

	wp_enqueue_script( 'foundation-script', get_template_directory_uri() . '/js/foundation.min.js', array( 'jquery' ), '201301', true  ); 
	wp_enqueue_script( 'responsive-tables-script', get_template_directory_uri() . '/js/responsive-tables.js', array( 'jquery' ), '201302', true  );
	wp_enqueue_script( 'fitvids-script', get_template_directory_uri() . '/js/jquery.fitvids.js', array( 'jquery' ), '201301', true  );
	wp_enqueue_script( 'theme-script', get_template_directory_uri() . '/js/tdsimple.js', array( 'jquery' ), '201301', true  );
	
}
add_action( 'wp_enqueue_scripts', 'tdsimple_scripts' );

/**
 *	Customize excerpts more tag
 *	@since tdsimple 1.0
 */
function tdsimple_excerpt_more($more) {
       global $post;
	return '... <a class="moretag" href="'. get_permalink($post->ID) . '"> <strong>Read More</strong></a>';
}
add_filter('excerpt_more', 'tdsimple_excerpt_more');

/**
*	Add Custom Meta Box (Featured Posts)
*	@since tdsimple 1.0
*/
function tdsimple_add_featured_metabox() {
    add_meta_box('tdsimple-featured-settings', 'Featured Post', 'tdsimple_featured_post', 'post', 'side', 'high');
    add_meta_box('tdsimple-featured-settings', 'Featured Post', 'tdsimple_featured_post', 'page', 'side', 'high');
}
add_action( 'add_meta_boxes', 'tdsimple_add_featured_metabox' );

/**
*	The Featured Post/Page Metabox
*	@since tdsimple 1.0
*/
function tdsimple_featured_post() {
    global $post;
    
    echo '<input type="hidden" name="tdsimple_featured_post_noncename" id="tdsimple_featured_post_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    
    $isFeaturedPost = get_post_meta($post->ID, '_tdsimple_featured_post', true);
    
    if($isFeaturedPost) {
    	  echo '
         	<select name="_tdsimple_featured_post">
         		<option value="0">No</option>
         		<option value="1" selected>Yes</option>
         	</select>
         ';
    } else {
    	echo '
         	<select name="_tdsimple_featured_post">
         		<option value="0" selected>No</option>
         		<option value="1">Yes</option>
         	</select>
         ';
    }
    
}

/**
*	Save Featured Post Metabox Data
*	@since tdsimple 1.0
*/
function tdsimple_save_featured_post_meta($post_id, $post) {
    /* verify metabox form */ 
    if ( !isset( $_POST['tdsimple_featured_post_noncename'] ) || !wp_verify_nonce( $_POST['tdsimple_featured_post_noncename'], plugin_basename(__FILE__) )) {
    	return $post->ID;
    }
    
    /* check if current user allowed to edit the post or page */
    if ( !current_user_can( 'edit_post', $post->ID )) {
    	return $post->ID;
    }
    
    /* Our save data */
    $events_meta['_tdsimple_featured_post'] = $_POST['_tdsimple_featured_post'];
    
    /* Add values of $events_meta as custom fields */
    foreach ($events_meta as $key => $value) { /* Cycle through the $events_meta array */
        if( $post->post_type == 'revision' ) return; /* Don't store custom data twice */
        
        $value = implode(',', (array)$value); /*  If $value is an array, make it a CSV (unlikely) */
        
        if(get_post_meta($post->ID, $key, FALSE)) { /* If the custom field already has a value */ 
            update_post_meta($post->ID, $key, $value);
        } else { /* If the custom field doesn't have a value */
            add_post_meta($post->ID, $key, $value);
        }
        
        if(!$value) delete_post_meta($post->ID, $key); /* Delete if blank */
    }
}
add_action('save_post', 'tdsimple_save_featured_post_meta', 1, 2);

/**
*	Rename Sticky Post Class
*	@since tdsimple 1.0
*/
function tdsimple_post_names($classes) {
	$classes = str_replace("sticky", "sticky-post", $classes);
	return $classes;
}
add_filter('post_class','tdsimple_post_names');

/**
*	Add class to excerpt paragraph
*	@since tdsimple 1.0
*/
function tdsimple_add_class_to_excerpt( $excerpt ) {
    return str_replace('<p', '<p class="excerpt"', $excerpt);
}
add_filter( "the_excerpt", "tdsimple_add_class_to_excerpt" );

/**
*	Add custome styles that were made in Theme Customizer
*	@since tdsimple 1.0.5
*/
function tdsimple_head() {
	$tdsimple_content_width = get_theme_mod( 'tdsimple_settings_content_width', 'large' );
	
	if( $tdsimple_content_width == 'small' ) {
		echo "
				<style type='text/css'> \n
					.row { width: auto; min-width: 0; max-width: 592px; }
				</style>
		     ";
	} else if( $tdsimple_content_width == 'medium' ) {
		echo "
				<style type='text/css'> \n
					.row { width: auto; min-width: 0; max-width: 768px; }
				</style>
		     ";
	} else {
		echo '';
	}
}
add_action('wp_head', 'tdsimple_head');

/* Admin Page that shows information about the theme */
add_action( 'admin_menu', 'register_my_custom_menu_page' );
function register_my_custom_menu_page(){
    add_theme_page( 'tdSimple Theme Info', 'tdSimple Info', 'manage_options', 'theme_information', 'tdsimple_theme_page_info' ); 
}

function tdsimple_theme_page_info(){
	$current_user = wp_get_current_user();
    echo '
    		<div class="wrap">
    			<h1>tdSimple - Theme Information</h1>
    			<br />
    			<div style="width: 600px; border: 1px solid #dfdfdf;">
    				<h2 style="font-weight: bold; padding: 25px 30px 10px">Hello, '.$current_user->display_name.'</h2>
    				<p style="padding: 0 30px;">Thank You for using my Theme. I hope it\'s working well for you. By the way, do you know that I build themes very often? You can subscribe to my newsletter and I’ll keep you up-to-date with news about my themes and special deals or updates. You can find a subscribe form on my website (bottom of the page)</p>
    				<p style="padding-left: 30px; margin-top: 25px;margin-bottom: 25px;"><a class="button button-primary" href="http://tasko.us/" target="_blank">Subscribe</a></p>
    				<h2 style="border-top: 1px solid #dfdfdf; font-weight: bold; padding: 25px 30px 10px">Something does not work? What I have to do?</h2>
    				<p style="padding: 0 30px;">If you have any questions, please post them on my website.</p>
    				<p style="padding-left: 30px; margin-top: 25px;margin-bottom: 25px;"><a class="button button-primary" href="http://tasko.us/support/" target="_blank">Support</a></p>
    				<h2 style="border-top: 1px solid #dfdfdf; font-weight: bold; padding: 25px 30px 10px">Changelog</h2>
    				<ul style="margin-left: 45px; margin-right: 30px; list-style: disc;">
    					<li><strong>v1.0.5</strong> - added feature that allows to upload logo and change content width. ( <em>Appearance</em> > <em>Themes</em> > <em>Customize</em> ). Fixed some mobile issues.</li>
    					<li><strong>v1.0.4</strong> - added feature that makes tables responsive.</li>
    					<li><strong>v1.0.1</strong> - added feature that makes videos responsive.</li>
    					<li><strong>v1.0</strong> - Initial release.</li>
    				</ul>
    			</div>
    	 	</div>
    	 ';	
}