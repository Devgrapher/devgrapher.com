<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package tdsimple
 * @since tdsimple 1.0
 */

if ( ! function_exists( 'tdsimple_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 *
 * @since tdsimple 1.0
 */
function tdsimple_content_nav( $nav_id ) {
	global $wp_query, $post;

	// Don't print empty markup on single pages if there's nowhere to navigate.
	if ( is_single() ) {
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous )
			return;
	}

	// Don't print empty markup in archives if there's only one page.
	if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
		return;

	$nav_class = 'site-navigation paging-navigation';
	if ( is_single() )
		$nav_class = 'site-navigation post-navigation';

	?>
	<nav role="navigation" id="<?php echo $nav_id; ?>" class="<?php echo $nav_class; ?>">
		<h1 class="assistive-text"><?php _e( 'Post navigation', 'tdsimple' ); ?></h1>

	<?php if ( is_single() ) : // navigation links for single posts ?>
		<!--
		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'tdsimple' ) . '</span> %title' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'tdsimple' ) . '</span>' ); ?>
		-->
		<?php wp_pagenavi(); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>
		<!--
		<?php if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'tdsimple' ) ); ?></div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'tdsimple' ) ); ?></div>
		<?php endif; ?> 
		-->
		<div class="nav-page"><?php wp_pagenavi(); ?></div>

	<?php endif; ?>

	</nav><!-- #<?php echo $nav_id; ?> -->
	<?php
}
endif; // tdsimple_content_nav

if ( ! function_exists( 'tdsimple_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since tdsimple 1.0
 */
function tdsimple_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'tdsimple' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'tdsimple' ), ' ' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer>
				<div class="comment-author vcard row">
					<p class="two columns avatar-container"><?php echo get_avatar( $comment, 126 ); ?></p>
					<div class="ten columns">
						<div class="row">
							<p class="twelve columns"><?php printf( __( '%s <span class="says">says:</span>', 'tdsimple' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?></p>
							<p class="twelve columns comment-meta commentmetadata">
								<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time datetime="<?php comment_time( 'c' ); ?>">
									<?php
										/* translators: 1: date, 2: time */
										printf( __( '%1$s at %2$s', 'tdsimple' ), get_comment_date(), get_comment_time() ); ?>
									</time>
								</a> / <?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?><!-- .comment-reply-link -->
								<?php edit_comment_link( __( '(Edit)', 'tdsimple' ), ' ' ); ?>
							</p>
						</div>
					</div>
				</div><!-- .comment-author .vcard -->
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<br />
					<span class="secondary label"><?php _e( 'Your comment is awaiting moderation.', 'tdsimple' ); ?></span>
				<?php endif; ?>

				<div class="comment-meta commentmetadata">
					
				</div><!-- .comment-meta .commentmetadata -->
			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for tdsimple_comment()

if ( ! function_exists( 'tdsimple_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since tdsimple 1.0
 */
function tdsimple_posted_on() {
	printf( __( '<time class="entry-date" datetime="%1$s"><a href="%6$s">%2$s</a></time><span class="byline"> by <span class="author vcard"><a class="url fn n" href="%3$s" title="%4$s" rel="author">%5$s</a></span></span>', 'tdsimple' ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'tdsimple' ), get_the_author() ) ),
		get_the_author(),
		get_permalink()
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category
 *
 * @since tdsimple 1.0
 */
function tdsimple_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so tdsimple_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so tdsimple_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in tdsimple_categorized_blog
 *
 * @since tdsimple 1.0
 */
function tdsimple_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'tdsimple_category_transient_flusher' );
add_action( 'save_post', 'tdsimple_category_transient_flusher' );

/**
 * Show Featured Posts in slider
 *
 * @since tdsimple 1.0
 */
 function tdsimple_slider() {
	global $post;
	
	/* settings for custom wp_query */
 	$args = array(
					'post_status' => 'publish',
					'post_type' => array('post', 'page'),
					'orderby' => 'post_date',
					'order' => 'DESC',
					"post__not_in"	 =>	get_option("sticky_posts"),
					'posts_per_page' => '5',
					'meta_query' => array(
         				array(
            					'key' => '_tdsimple_featured_post',
            					'value' => '1'
							)
					 )
				);
				
	$slider_posts = new WP_Query($args);
	
	ob_start(); 
	
	if($slider_posts->have_posts()) :
	
		echo '<div class="twelve columns">';
				_e('<h4 class="featured-title">Featured Posts</h4>', 'tdsimple');
		echo '</div>';
				
	
		echo '<div class="twelve columns"><ul id="slider">';

		while($slider_posts->have_posts()) : $slider_posts->the_post();
			if ( has_post_thumbnail() ) {
				$image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $slider_posts->post->ID ), 'single-post-thumbnail' );
				$featured_image = $image_url[0];
			} else {
				$featured_image = "";
			}
			echo '
						<li>
							<img src="'.$featured_image.'" alt="'.get_the_title().'">
							<div class="rs-caption rs-bottom info">
            					<h4><a href="'.post_permalink().'">'.get_the_title().'</a></h4>
        					</div>
						</li>
			     ';
		
		endwhile;
		
		echo '</ul></div>';
		
	endif;
	
	$output = ob_get_contents();
	ob_end_clean();
	
	wp_reset_postdata();
	
	return $output;
 }