<?php
/**
 * WordPress.com-specific functions and definitions
 * This file is centrally included from `wp-content/mu-plugins/wpcom-theme-compat.php`.
 *
 * @package Hexa
 */

function hexa_theme_colors() {
	global $themecolors;

	/**
	 * Set a default theme color array for WP.com.
	 *
	 * @global array $themecolors
	 */
	if ( ! isset( $themecolors ) ) :
		$themecolors = array(
			'bg' => 'e9e7e3',
			'border' => 'bcb6aa',
			'text' => '6a524a',
			'link' => 'd25349',
			'url' => 'd25349',
		);
	endif;
}
add_action( 'after_setup_theme', 'hexa_theme_colors' );

/*
 * De-queue Google fonts if custom fonts are being used instead
 */
function hexa_dequeue_fonts() {
	if ( class_exists( 'TypekitData' ) && class_exists( 'CustomDesign' ) && CustomDesign::is_upgrade_active() ) {
		$customfonts = TypekitData::get( 'families' );
		if ( $customfonts && $customfonts['site-title']['id'] && $customfonts['headings']['id'] && $customfonts['body-text']['id'] ) {
			wp_dequeue_style( 'hexa-source-sans-pro' );
		}
	}
}

add_action( 'wp_enqueue_scripts', 'hexa_dequeue_fonts' );

/*
 * WordPress.com print styles & responsive videos
 */

function hexa_theme_support() {
	add_theme_support( 'wpcom-responsive-videos' );
	add_theme_support( 'print-style' );
}
add_action( 'after_setup_theme', 'hexa_theme_support' );



//WordPress.com specific styles
function hexa_wpcom_styles() {
	wp_enqueue_style( 'hexa-wpcom', get_template_directory_uri() . '/inc/style-wpcom.css', '140217' );
}
add_action( 'wp_enqueue_scripts', 'hexa_wpcom_styles' );