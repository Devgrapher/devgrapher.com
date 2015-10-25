<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package harmonic
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function harmonic_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'harmonic_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function harmonic_body_classes( $classes ) {

	// Add for Skrollr plugin
	$classes[] = 'loading';

	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'secondary-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'harmonic_body_classes' );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function harmonic_wp_title( $title, $sep ) {
	if ( is_feed() ) {
		return $title;
	}
	global $page, $paged;

	// Add the blog name
	$title .= get_bloginfo( 'name', 'display' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 ) {
		$title .= " $sep " . sprintf( __( 'Page %s', 'harmonic' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'harmonic_wp_title', 10, 2 );

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function harmonic_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'harmonic_setup_author' );

/**
 * Return the post URL to use as link for h1 on link post formats.
 *
 * @uses get_url_in_content() to get the URL in the post meta (if it exists) or
 * the first link found in the post content.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 * @return string The Link format URL.
 */
function harmonic_get_link_url() {
	$content = get_the_content();
	$check_url = get_url_in_content( $content );

	return ( $check_url ) ? $check_url : apply_filters( 'the_permalink', get_permalink() );
}

if ( ! function_exists( 'harmonic_continue_reading_link' ) ) :
/**
 * Returns an ellipsis rather than continue reading link
 */
function harmonic_continue_reading_link() {
	return '&hellip;<a href="'. esc_url( get_permalink() ) . '" class="button">' . sprintf( __( 'Read more <span class="screen-reader-text">%1$s</span>', 'harmonic' ), esc_attr( strip_tags( get_the_title() ) ) ) . '</a>';
}
endif;

if ( ! function_exists( 'harmonic_excerpt_link' ) ) :
/**
 * Returns an ellipsis for excerpts - can be overriden in child theme
 */
function harmonic_excerpt_link() {
	return harmonic_continue_reading_link();
}
add_filter( 'excerpt_more', 'harmonic_excerpt_link' );
endif;