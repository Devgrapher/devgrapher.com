<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Hexa
 */

if ( ! function_exists( 'hexa_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @return void
 */
function hexa_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'hexa' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( _x( '<span class="meta-nav screen-reader-text">&larr;</span>', 'Older posts', 'hexa' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( _x( '<span class="meta-nav screen-reader-text">&rarr;</span>', 'Newer posts', 'hexa' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'hexa_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @return void
 */
function hexa_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'hexa' ); ?></h1>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav screen-reader-text">&larr;</span>', 'Previous post link', 'hexa' ) );
				next_post_link(     '<div class="nav-next">%link</div>',     _x( '<span class="meta-nav screen-reader-text">&rarr;</span>', 'Next post link',     'hexa' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'hexa_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function hexa_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	$args['avatar_size'] = 60;

	if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<div class="comment-body">
			<?php _e( 'Pingback:', 'hexa' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'hexa' ), '<span class="edit-link">', '</span>' ); ?>
		</div>

	<?php else : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php if ( 0 != $args['avatar_size'] ) { echo '<span class="avatar-wrapper">' . get_avatar( $comment, $args['avatar_size'] ) . '</span>'; } ?>
					<?php printf( __( '%s <span class="says">says:</span>', 'hexa' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
				</div><!-- .comment-author -->

				<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'hexa' ); ?></p>
				<?php endif; ?>
			</footer><!-- .comment-meta -->

			<div class="comment-content">
				<?php comment_text(); ?>
			</div><!-- .comment-content -->

			<div class="comment-metadata">
				<?php
					comment_reply_link( array_merge( $args, array(
						'add_below' => 'div-comment',
						'depth'     => $depth,
						'max_depth' => $args['max_depth'],
						'before'    => '<div class="reply">',
						'after'     => '</div>',
					) ) );
				?>
				<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
					<time datetime="<?php comment_time( 'c' ); ?>">
						<span class="post-date"><?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'hexa' ), get_comment_date(), get_comment_time() ); ?></span>
					</time>
				</a>
				<?php edit_comment_link( __( 'Edit', 'hexa' ), '<span class="edit-link">', '</span>' ); ?>
			</div><!-- .comment-metadata -->
		</article><!-- .comment-body -->

	<?php
	endif;
}
endif; // ends check for hexa_comment()

if ( ! function_exists( 'hexa_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function hexa_posted_on() {
	if ( is_sticky() && ! is_single() ) {
		printf( __( '<span class="post-date"><a href="%1$s" title="%2$s" rel="bookmark">Featured</a></span><span class="byline"><span class="author vcard"><a class="url fn n" href="%3$s" title="%4$s" rel="author">%5$s</a></span></span>', 'hexa' ),
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'hexa' ), get_the_author() ) ),
			esc_html( get_the_author() )
		);
	}
	else {
		printf( __( '<span class="post-date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span><span class="byline"><span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'hexa' ),
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'hexa' ), get_the_author() ) ),
			get_the_author()
		);
	}
}
endif;