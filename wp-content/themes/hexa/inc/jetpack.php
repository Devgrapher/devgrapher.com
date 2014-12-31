<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Hexa
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function hexa_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'footer'    => 'content',
	) );
}
add_action( 'after_setup_theme', 'hexa_jetpack_setup' );
