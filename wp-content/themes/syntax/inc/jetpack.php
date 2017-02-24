<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Syntax
 */

function syntax_jetpack_setup() {
	/**
	 * Add theme support for Infinite Scroll.
	 * See: http://jetpack.me/support/infinite-scroll/
	 */
	add_theme_support( 'infinite-scroll', array(
		'container' => 'content',
		'footer'    => 'content',
		'footer_widgets' => array( 'sidebar-1', 'sidebar-2', 'sidebar-3' ),
	) );

	/**
	 * Add theme support for Responsive Videos.
	 */
	add_theme_support( 'jetpack-responsive-videos' );

	add_theme_support( 'social-links', array(
		'facebook', 'twitter', 'linkedin', 'google_plus', 'tumblr', 'path'
	) );
}
add_action( 'after_setup_theme', 'syntax_jetpack_setup' );
