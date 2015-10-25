<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package harmonic
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function harmonic_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container'      => 'archive-container',
		'footer'         => 'page',
		'render'         => 'harmonic_infinite_scroll_render',
	) );

	/**
	 * Add theme support for Responsive Videos.
	 */
	add_theme_support( 'jetpack-responsive-videos' );

	/**
	 * Add theme support for Portfolio Custom Post Type.
	 */
	add_theme_support( 'jetpack-portfolio' );

	/**
	 * Add theme support for Logo upload.
	 */
	add_image_size( 'harmonic-logo', 1000, 250 );
	add_theme_support( 'site-logo', array( 'size' => 'harmonic-logo' ) );
}
add_action( 'after_setup_theme', 'harmonic_jetpack_setup' );

/**
 * Define the code that is used to render the posts added by Infinite Scroll.
 *
 * Includes the whole loop. Used to include the correct template part for the Portfolio CPT.
 */
function harmonic_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();

		if ( is_post_type_archive( 'jetpack-portfolio' ) || is_tax( 'jetpack-portfolio-type' ) || is_tax( 'jetpack-portfolio-tag' ) ) {
			get_template_part( 'content', 'portfolio' );
		} else {
			get_template_part( 'content', get_post_format() );
		}
	}
}

if ( function_exists( 'jetpack_is_mobile' ) ) {

	function harmonic_infinite_scroll_has_footer_widgets() {
		if ( jetpack_is_mobile( '', true ) && is_active_sidebar( 'sidebar-1' ) ) {
			return true;
		}

		return false;
	}

	add_filter( 'infinite_scroll_has_footer_widgets', 'harmonic_infinite_scroll_has_footer_widgets' );
}

/**
 * Return early if Site Logo is not available.
 */
function harmonic_the_site_logo() {
	if ( ! function_exists( 'jetpack_the_site_logo' ) ) {
		return;
	} else {
		jetpack_the_site_logo();
	}
}