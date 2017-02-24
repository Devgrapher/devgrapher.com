<?php
/**
 * Readable functions and definitions
 *
 * @package Syntax
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 1008; /* pixels */

if ( ! function_exists( 'syntax_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function syntax_setup() {

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Readable, use a find and replace
	 * to change 'syntax' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'syntax', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails on posts and pages
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( '1008', '9999' );

	/*
	 * Add support for TinyMCE styles
	 */
	add_editor_style();

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary'   => __( 'Primary Menu', 'syntax' ),
		'secondary' => __( 'Footer Menu', 'syntax' ),
	) );

	$args = array(
		'default-color' => 'ffffff',
		'default-image' => '',
	);

	$args = apply_filters( 'syntax_custom_background_args', $args );

	add_theme_support( 'custom-background', $args );

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link', 'gallery' ) );
}
endif; // syntax_setup
add_action( 'after_setup_theme', 'syntax_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function syntax_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Footer Sidebar 1', 'syntax' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Sidebar 2', 'syntax' ),
		'id'            => 'sidebar-2',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Sidebar 3', 'syntax' ),
		'id'            => 'sidebar-3',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'syntax_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function syntax_scripts() {
	wp_enqueue_style( 'syntax-style', get_stylesheet_uri() );

	wp_enqueue_style( 'syntax-merriweather' );

	wp_enqueue_script( 'syntax-siteheader', get_template_directory_uri() . '/js/siteheader.js', array( 'jquery' ), '20120206', true );

	wp_enqueue_script( 'syntax-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'syntax-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', 'syntax_scripts' );

/**
 * Register Google Fonts
 */
function syntax_google_fonts() {

	$protocol = is_ssl() ? 'https' : 'http';

	/*	translators: If there are characters in your language that are not supported
		by Merriweather, translate this to 'off'. Do not translate into your own language. */

	if ( 'off' !== _x( 'on', 'Merriweather font: on or off', 'syntax' ) ) {

		wp_register_style( 'syntax-merriweather', "$protocol://fonts.googleapis.com/css?family=Merriweather:400,300italic,300,400italic,700,700italic&subset=latin,latin-ext" );

	}
}
add_action( 'init', 'syntax_google_fonts' );

/**
 * Enqueue Google Fonts for custom headers
 */
function syntax_admin_scripts( $hook_suffix ) {

	if ( 'appearance_page_custom-header' != $hook_suffix )
		return;

	wp_enqueue_style( 'syntax-merriweather' );

}
add_action( 'admin_enqueue_scripts', 'syntax_admin_scripts' );

/**
 * Adds additional stylesheets to the TinyMCE editor if needed.
 *
 * @param string $mce_css CSS path to load in TinyMCE.
 * @return string
 */
function syntax_mce_css( $mce_css ) {

	$protocol = is_ssl() ? 'https' : 'http';

	$font = "$protocol://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic";

	if ( empty( $font ) )
		return $mce_css;

	if ( ! empty( $mce_css ) )
		$mce_css .= ',';

	$font= esc_url_raw( str_replace( ',', '%2C', $font ) );

	return $mce_css . $font;
}
add_filter( 'mce_css', 'syntax_mce_css' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( file_exists( get_template_directory() . '/inc/jetpack.php' ) )
	require get_template_directory() . '/inc/jetpack.php';
