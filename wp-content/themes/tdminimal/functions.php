<?php
/**
 * tdMinimal functions and definitions
 *
 * @package tdMinimal
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( 'tdminimal_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function tdminimal_setup() {

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on tdMinimal, use a find and replace
	 * to change 'tdminimal' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'tdminimal', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails on posts and pages
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails', array( 'post', 'page', 'product' ) );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'tdminimal' ),
	) );

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'image', 'gallery', 'video', 'audio', 'quote', 'link', 'chat', 'status' ) );
	
	if ( function_exists( 'add_image_size' ) ) { 
		add_image_size( 'front-page-thumb', 880, 570, true );
	}
}
endif; // tdminimal_setup
add_action( 'after_setup_theme', 'tdminimal_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function tdminimal_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'tdminimal' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	
	if( tdminimal_is_footer_widgets() ) {
		register_sidebar( array(
			'name'          => __( 'Footer #1', 'tdminimal' ),
			'id'            => 'footer-1',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );
		
		register_sidebar( array(
			'name'          => __( 'Footer #2', 'tdminimal' ),
			'id'            => 'footer-2',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );
		
		register_sidebar( array(
			'name'          => __( 'Footer #3', 'tdminimal' ),
			'id'            => 'footer-3',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );
	}
}
add_action( 'widgets_init', 'tdminimal_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function tdminimal_scripts() {
	
	wp_enqueue_style( 'tdminimal-google-fonts', '//fonts.googleapis.com/css?family=Raleway:400,600,900' );
	wp_enqueue_style( 'tdminimal-google-fonts-body', '//fonts.googleapis.com/css?family=Lora:400,700' );
	wp_enqueue_style( 'tdminimal-css-framework', get_template_directory_uri() . '/css/bootstrap.min.css' );
	wp_enqueue_style( 'tdminimal-icons-ie', get_template_directory_uri() . '/css/font-awesome-ie7.min.css' );
	wp_enqueue_style( 'tdminimal-icons', get_template_directory_uri() . '/css/font-awesome.min.css' );
	
	if( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { 
		wp_enqueue_style('woocommerce-css', plugins_url() .'/woocommerce/assets/css/woocommerce.css' ); 
	}
	
	wp_enqueue_style( 'tdminimal-style', get_stylesheet_uri() );
	
	if ( tdminimal_get_color_scheme() ) {
		wp_enqueue_style( 'tdminimal-color-scheme', get_template_directory_uri() . '/css/color-scheme/'.tdminimal_get_color_scheme().'.css' );
	}

	wp_enqueue_script( 'tdminimal-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );
	wp_enqueue_script( 'tdminimal-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'tdminimal-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
	
	if( !is_singular() && tdminimal_is_infinite_scroll() ) {
		wp_enqueue_script( 'tdminimal-infinitescroll-script', get_template_directory_uri() . '/js/jquery.infinitescroll.min.js', array( 'jquery' ), '201301' );
	}
	
	if( (is_home() || is_archive() || is_search() ) && !wp_script_is( 'wp-mediaelement', 'done' ) ) {
		wp_enqueue_style('wp-mediaelement');
		wp_enqueue_script( 'wp-mediaelement' );  
	}

	wp_enqueue_script( 'tdminimal-assets', get_template_directory_uri() . '/js/jquery.assets.js', array( 'jquery' ), '201301'  );
	wp_enqueue_script( 'tdminimal-script', get_template_directory_uri() . '/js/tdminimal.js', array( 'jquery' ), '201301', true  );
	
	$tdminimal_params = array(
		'backgroundImages' => tdminimal_is_background_image(),
		'fixedMenu' => tdminimal_is_fixed_menu(),
		'smoothScroll' => tdminimal_is_smooth_scroll(),
		'infinitescroll' => tdminimal_is_infinite_scroll(),
		'infinitescrollImg' => get_template_directory_uri() . '/images/loader.gif',
		'infinitescrollLoadMsg' => __( 'Loading the next set of posts', 'tdminimal' ),
		'infinitescrollFinishedMsg' => __( 'All posts loaded.', 'tdminimal' ),
		'isMasonryLayout' => tdminimal_is_masonry_layout()
	);
	
	wp_localize_script( 'tdminimal-script', 'tdminimalParams', $tdminimal_params );
}
add_action( 'wp_enqueue_scripts', 'tdminimal_scripts' );

/**
 * Custom template tags for this theme.
 */
require_once get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require_once get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require_once get_template_directory() . '/inc/customizer.php';

/**
 * Custom Widgets
 */
require_once get_template_directory() . '/inc/widgets.php';

/**
 * Slightly Modified Options Framework
 */
require_once ('admin/index.php');

/**
 * WooCommerce
 */
if( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	require get_template_directory() . '/inc/theme-woocommerce.php';
}

/**
 * Change bbPress Forum Title
 * @since tdminimal 1.0.5
 */
if ( class_exists( 'bbPress' ) ) { 
	function tdminimal_set_forum_title() {
		return tdminimal_get_forum_title();
	}
	add_filter('bbp_get_forum_archive_title', 'tdminimal_set_forum_title');
}

/**
 * Enqueue Admin scripts
 * @since tdminimal 1.0.5
 */
function tdminimal_enqueue_color_picker( ) {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker-script', get_template_directory_uri() . '/js/tdminimal-admin.js', array( 'wp-color-picker' ), false, true );
}
add_action( 'admin_enqueue_scripts', 'tdminimal_enqueue_color_picker' );

/**
 *	Add custom style/script to website head
 *	@since tdminimal 1.0
 */
function tdminimal_head() {
	echo '<!--[if lt IE 9]><script src="'.get_template_directory_uri().'/js/html5.js"></script> <script src="'.get_template_directory_uri().'/js/respond.min.js"></script><![endif]-->' . "\n";
	tdminimal_custom_styles();
}
add_action('wp_head', 'tdminimal_head');

/**
 *	Remove text after comment textarea
 *	@since tdminimal 1.0
 */
function tdminimal_remove_comment_styling_prompt( $defaults ) {
	$defaults['comment_notes_after'] = '';
	return $defaults;
}
add_filter('comment_form_defaults', 'tdminimal_remove_comment_styling_prompt');

/**
 *	Make Audio Player responsive
 *	@since tdminimal 1.0
 */
function tdminimal_audio_shortcode( $html ){
	return str_replace('<audio', '<audio width="100%" height="100%" style="width: 100%; height: 100%;"', $html);
}
add_filter('wp_audio_shortcode', 'tdminimal_audio_shortcode');

/**
 *	Make Video Player responsive
 *	@since tdminimal 1.0
 */
function tdminimal_video_shortcode( $html ){
	return str_replace('<video', '<video width="100%" height="100%" style="width: 100%; height: 100%;"', $html);
}
add_filter('wp_video_shortcode', 'tdminimal_video_shortcode');

/**
 *	Add Custom Social Fields to User Profile Page
 *	@since tdminimal 1.0
 */
function tdminimal_custom_contact_fields( $profile_fields ) {
	$profile_fields['twitter'] = __( 'Twitter Username', 'tdminimal' );
	$profile_fields['facebook'] = __( 'Facebook URL', 'tdminimal' );
	$profile_fields['gplus'] = __( 'Google+ URL', 'tdminimal' );
	$profile_fields['linkedin'] = __( 'LinkedIn URL', 'tdminimal' );
	$profile_fields['instagram'] = __( 'Instagram URL', 'tdminimal' );

	return $profile_fields;
}
add_filter('user_contactmethods', 'tdminimal_custom_contact_fields');

/**
 *	Customize excerpts more tag
 *	@since tdminimal 1.0
 */
function tdminimal_excerpt_more( $more ) {
    global $post;
	return '...' . ' <a class="continue-reading-link" href="'.esc_url( get_permalink( $post->ID ) ).'">'.__( 'More', 'tdminimal' ).'</a>';
}
add_filter('excerpt_more', 'tdminimal_excerpt_more');

/**
 *	Change Excerpt Length
 *	@since tdminimal 1.0
 */
function tdminimal_excerpt_length( $length ) {
	return tdminimal_get_excerpt_length();
}
add_filter( 'excerpt_length', 'tdminimal_excerpt_length', 999 );

/**
 * Add an additional Content to Post/Page
 *
 * @since tdminimal 1.0.3
 */
function tdminimal_custom_content( $content ) {
	global $post;
	$post_page_intro = get_post_meta( $post->ID, '_tdminimal_post_page_intro', true );
	$post_content_ad = tdminimal_post_content_ad();
	
    if ( ( is_single() || is_page() ) && !is_attachment() && $post_page_intro != '' ) {
    	$output = '<div class="entry-intro">';
		$output .= wpautop( $post_page_intro );
		$output .= '</div>';
        $content = $output.$content;    
    }
    
    if( is_single() && $post_content_ad != '' ) {
    	$content = $post_content_ad.$content;
    }

    return $content;
}
add_filter( 'the_content', 'tdminimal_custom_content', 20 );