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
	add_theme_support( 'jetpack-portfolio', array(
		'title'          => true,
		'content'        => true,
		'featured-image' => true,
	) );

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

if ( ! function_exists( 'harmonic_the_site_logo' ) ) :
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
endif;

/**
 * Portfolio Title.
 */
function harmonic_portfolio_title( $before = '', $after = '' ) {
	$jetpack_portfolio_title = get_option( 'jetpack_portfolio_title' );
	$title = '';

	if ( is_post_type_archive( 'jetpack-portfolio' ) ) {
		if ( isset( $jetpack_portfolio_title ) && '' != $jetpack_portfolio_title ) {
			$title = esc_html( $jetpack_portfolio_title );
		} else {
			$title = post_type_archive_title( '', false );
		}
	} elseif ( is_tax( 'jetpack-portfolio-type' ) || is_tax( 'jetpack-portfolio-tag' ) ) {
		$title = single_term_title( '', false );
	}

	echo $before . $title . $after;
}

/**
 * Portfolio Content.
 */
function harmonic_portfolio_content( $before = '', $after = '' ) {
	$jetpack_portfolio_content = get_option( 'jetpack_portfolio_content' );

	if ( is_tax() && get_the_archive_description() ) {
		echo $before . get_the_archive_description() . $after;
	} else if ( isset( $jetpack_portfolio_content ) && '' != $jetpack_portfolio_content ) {
		$content = convert_chars( convert_smilies( wptexturize( stripslashes( wp_filter_post_kses( addslashes( $jetpack_portfolio_content ) ) ) ) ) );
		echo $before . $content . $after;
	}
}

/**
 * Portfolio Featured Image.
 */
function harmonic_portfolio_thumbnail( $before = '', $after = '' ) {
	$jetpack_portfolio_featured_image = get_option( 'jetpack_portfolio_featured_image' );

	if ( isset( $jetpack_portfolio_featured_image ) && '' != $jetpack_portfolio_featured_image ) {
		$featured_image = wp_get_attachment_image( (int) $jetpack_portfolio_featured_image, 'portfolio-thumbnail' );
		echo $before . $featured_image . $after;
	}
}

/**
 * Filter Infinite Scroll text handle.
 */
function harmonic_portfolio_infinite_scroll_navigation( $js_settings ) {
	if ( is_post_type_archive( 'jetpack-portfolio' ) || is_tax( 'jetpack-portfolio-type' ) || is_tax( 'jetpack-portfolio-tag' ) ) {
		$js_settings[ 'text' ] = esc_js( esc_html__( 'Older projects', 'harmonic' ) );
	}

	return $js_settings;
}
add_filter( 'infinite_scroll_js_settings', 'harmonic_portfolio_infinite_scroll_navigation' );