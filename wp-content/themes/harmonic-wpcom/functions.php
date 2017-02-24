<?php
/**
 * harmonic functions and definitions
 *
 * @package harmonic
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 662; /* pixels */
}

if ( ! function_exists( 'harmonic_content_width' ) ) :

function harmonic_content_width() {

	global $content_width;

	if ( is_page_template( 'templates/front-page.php' ) )
		$content_width = 790;

	if ( is_page() || is_page_template( 'templates/fullwidth-page.php' ) )
		$content_width = 882;

	if ( is_page_template( 'templates/portfolio-page.php' ) )
		$content_width = 1216;
}
add_action( 'template_redirect', 'harmonic_content_width' );

endif;

if ( ! function_exists( 'harmonic_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function harmonic_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 */
	load_theme_textdomain( 'harmonic', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'harmonic-featured-image', 1024, 9999 );
	add_image_size( 'portfolio-landscape', 480, 360, true );
	add_image_size( 'portfolio-portrait', 480, 640, true );
	add_image_size( 'portfolio-square', 480, 480, true );
	add_image_size( 'portfolio-thumbnail', 1216, 9999 );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'harmonic' ),
		'social'  => __( 'Social Links Menu', 'harmonic' ),
	) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array( 'comment-list', 'search-form', 'comment-form', ) );

	// Enable and setup with defaults the custom background.
	add_theme_support( 'custom-background', apply_filters( 'harmonic_custom_background_args', array(
		'default-color'      => 'fff',
	) ) );

	// Adds editor support
	add_editor_style( array( 'editor-style.css', 'fonts/genericons.css', harmonic_hind_font_url() ) );

}
endif; // _s_setup
add_action( 'after_setup_theme', 'harmonic_setup' );

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function harmonic_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'harmonic' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Front page', 'harmonic' ),
		'id'            => 'sidebar-2',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'harmonic_widgets_init' );

/**
 * Returns the Google font stylesheet URL, if available.
 *
 * @since harmonic 1.0
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function harmonic_hind_font_url() {

	$harmonic_hind_font_url = '';

	/* translators: If there are characters in your language that are not supported
	   by Arimo, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Hind sans-serif font: on or off', 'harmonic' ) ) {

		$subsets = 'latin,latin-ext';

		/* translators: To add an additional Armio character subsets,
		 * translate this to 'devangari'.
		 * Do not translate these strings into your own langauge.
		 */
		$subset = _x( 'no-subset', 'Hind: add new subset (devanagari)', 'harmonic' );

		if ( 'devanagari' == $subset ) {
			$subsets .= 'devanagari';
		}

		$query_args = array(
			'family' => urlencode( 'Hind:300,400,500,600,700' ),
			'subset' => urlencode( $subsets ),
		);

		$harmonic_hind_font_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

	}

	return $harmonic_hind_font_url;
}

/**
 * Enqueue Google Fonts for admin
 */
function harmonic_admin_fonts() {
	wp_enqueue_style( 'harmonic_adminhind', harmonic_hind_font_url(), array(), null );
}
add_action( 'admin_print_scripts-appearance_page_custom-header', 'harmonic_admin_fonts' );


/**
 * Enqueue scripts and styles.
 */
function harmonic_scripts() {
	wp_enqueue_style( 'harmonic-style', get_stylesheet_uri() );

	wp_enqueue_script( 'harmonic-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20141202', true );

	// Scripts for scrolling front page template
	if ( is_page_template( 'templates/front-page.php' ) ||  is_single() ||  is_page() ) {
		wp_enqueue_script( 'skrollr', get_template_directory_uri() . '/js/skrollr.js', array( 'jquery' ), '20141207', true );
		wp_enqueue_script( 'imagesloaded', get_template_directory_uri() . '/js/imagesloaded.js', array( 'jquery' ), '20141207', true );
		wp_enqueue_script( 'skrollr-menu', get_template_directory_uri() . '/js/skrollr.menu.js', array( 'skrollr' ), '20141807', true );
		wp_enqueue_script( 'enquire', get_template_directory_uri() . '/js/enquire.js', array( 'skrollr' ), '20141207', true );
		wp_enqueue_script( 'harmonic-skrollr', get_template_directory_uri() . '/js/harmonic-skrollr.js', array( 'enquire' ), '20141807', true );
	}
	wp_enqueue_script( 'harmonic-scripts', get_template_directory_uri() . '/js/harmonic.js', array( 'jquery' ), '20141202', true );

	wp_enqueue_style( 'harmonic-hind', harmonic_hind_font_url(), array(), null );

	if ( is_page_template( 'templates/portfolio-page.php' ) || is_post_type_archive( 'jetpack-portfolio' ) || is_tax( 'jetpack-portfolio-type' ) || is_tax( 'jetpack-portfolio-tag' ) ) {
		wp_enqueue_script( 'harmonic-masonry', get_template_directory_uri() . '/js/harmonic-masonry.js', array( 'masonry' ), '20141002', true );
	}

	if ( wp_style_is( 'genericons', 'registered' ) )
		wp_enqueue_style( 'genericons' );
	else
		wp_enqueue_style( 'genericons', get_template_directory_uri() . '/css/genericons.css', array(), null );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
add_action( 'wp_enqueue_scripts', 'harmonic_scripts' );

/**
 * Sets up the custom header script for the background on archive pages
 */
function harmonic_enqueue_backstretch() {

	$headerimage = '';
	$header_image = get_header_image();

	if ( ! empty( $header_image ) ) {
		$headerimage = $header_image;
	}

	wp_enqueue_script( 'harmonic-backstretch', get_template_directory_uri() . '/js/jquery.backstretch.js', array( 'jquery' ), '1.0.0' );
	wp_enqueue_script( 'harmonic-backstretch-set', get_template_directory_uri() .'/js/harmonic-backstretch.js' , array( 'jquery', 'harmonic-backstretch' ), '1.0.0' );
	wp_localize_script( 'harmonic-backstretch-set', 'BackStretchImg', array(
			'src' => $headerimage ) );
}
add_action( 'wp_enqueue_scripts', 'harmonic_enqueue_backstretch' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';


// updater for WordPress.com themes
if ( is_admin() )
	include dirname( __FILE__ ) . '/inc/updater.php';
