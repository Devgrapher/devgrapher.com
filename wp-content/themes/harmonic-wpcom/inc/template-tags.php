<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package harmonic
 */

if ( ! function_exists( 'harmonic_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @return void
 */
function harmonic_paging_nav( $max_num_pages = '' ) {
	// Get max_num_pages if not provided
	if ( '' == $max_num_pages )
		$max_num_pages = $GLOBALS['wp_query']->max_num_pages;

	// Don't print empty markup if there's only one page.
	if ( $max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation clear" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'harmonic' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link( '', $max_num_pages ) ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">Previous</span>', 'harmonic' ), $max_num_pages ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link( '', $max_num_pages ) ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( '<span class="meta-nav">Next</span>', 'harmonic' ), $max_num_pages ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;


if ( ! function_exists( 'harmonic_portfolio_pagination' ) ) :
function harmonic_portfolio_pagination() {
	global $post;
	$pagination_posts = array();

	$pagination_posts['previous'] = get_adjacent_post( false, '', true );
	$pagination_posts['next']     = get_adjacent_post( false, '', false );

	if ( ! $pagination_posts['previous'] && ! $pagination_posts['next'] )
		return false;
?>
	<nav class="navigation project-navigation clear" role="navigation">
		<h1 class="more-projects"><?php _e( 'More Projects', 'harmonic' ); ?></h1>
		<div class="project-navigation-wrapper clear">
			<ul>
			<?php
				foreach ( $pagination_posts as $pagination_post => $post ) :
					if ( is_object( $post ) && ( $post instanceof WP_Post ) && 'jetpack-portfolio' == $post->post_type ) :
						setup_postdata( $post );
			?>
						<li class="<?php echo esc_attr( $pagination_post ); ?>">
							<?php get_template_part( 'content', 'portfolio' ); ?>
						</li>
			<?php
						wp_reset_postdata();
					else :
			?>
						<li class="<?php echo esc_attr( $pagination_post ); ?>">
							<div class="no-result jetpack-portfolio">
								<div class="post-thumbnail-wrapper"></div>
							</div>
						</li>
			<?php
					endif;
				endforeach;
			?>
			</ul>
		</div>
	</nav>
<?php
}
endif;

if ( ! function_exists( 'harmonic_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @return void
 */
function harmonic_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation clear" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'harmonic' ); ?></h1>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', _x( 'Previous', 'Previous post link', 'harmonic' ) );
				next_post_link(     '<div class="nav-next">%link</div>',     _x( 'Next', 'Next post link',     'harmonic' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'harmonic_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function harmonic_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	$args['avatar_size'] = 40;

	if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<div class="comment-body">
			<?php _e( 'Pingback:', 'harmonic' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'harmonic' ), '<span class="edit-link">', '</span>' ); ?>
		</div>

	<?php else : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<div class="comment-author vcard">
				<?php if ( 0 != $args['avatar_size'] ) { echo '<span class="avatar-wrapper">' . get_avatar( $comment, $args['avatar_size'] ) . '</span>'; } ?>
			</div><!-- .comment-author -->
			<div class="comment-wrapper">
				<div class="comment-content">
					<?php comment_text(); ?>
				</div><!-- .comment-content -->
				<footer class="comment-meta">

				<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'harmonic' ); ?></p>
				<?php endif; ?>

				<?php printf( __( '%s ', 'harmonic' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>

				<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
					<time datetime="<?php comment_time( 'c' ); ?>">
						<span class="post-date"><?php printf( _x( ', %1$s at %2$s', '1: date, 2: time', 'harmonic' ), get_comment_date(), get_comment_time() ); ?></span>
					</time>
				</a>
				<?php edit_comment_link( __( ' (Edit)', 'harmonic' ), '<span class="edit-link">', '</span>' ); ?>
				<?php
					comment_reply_link( array_merge( $args, array(
						'add_below' => 'div-comment',
						'depth'     => $depth,
						'max_depth' => $args['max_depth'],
						'before'    => '<div class="reply">',
						'after'     => '</div>',
					) ) );
				?>

				</footer><!-- .comment-meta -->
			</div>
		</article><!-- .comment-body -->

	<?php
	endif;
}
endif; // ends check for harmonic_comment()

if ( ! function_exists( 'harmonic_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function harmonic_posted_on() {
	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	printf( __( '<span class="posted-on">%1$s</span>', 'harmonic' ),
		sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>',
			esc_url( get_permalink() ),
			$time_string
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		)
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 */
function harmonic_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so harmonic_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so harmonic_categorized_blog should return false.
		return false;
	}
}

/**
 * Outputs this theme's special thumbnail sizes
 */
if ( ! function_exists( 'harmonic_post_thumbnail' ) ) :
function harmonic_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	if ( is_singular() || ( ! is_search() && 'jetpack-portfolio' == get_post_type() ) ) :

	?>

	<div class="post-thumbnail">
	<?php
		if ( 'jetpack-portfolio' == get_post_type() ) :
			$ratio = get_theme_mod( 'harmonic_portfolio_thumbnail' );
			switch ( $ratio ) {
				case 'portrait':
					the_post_thumbnail( 'portfolio-portrait' );
					break;
				case 'square':
					the_post_thumbnail( 'portfolio-square' );
					break;
				default :
					the_post_thumbnail( 'portfolio-landscape' );
			}
		else :
			the_post_thumbnail();
		endif;
	?>
	</div>

	<?php else : ?>

	<a class="post-thumbnail" href="<?php the_permalink(); ?>">
	<?php the_post_thumbnail(); ?>
	</a>

	<?php endif; // End is_singular()
}

endif; //harmonic_post_thumbnail

/**
 * Flush out the transients used in harmonic_categorized_blog.
 */
function harmonic_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'harmonic_category_transient_flusher' );
add_action( 'save_post',     'harmonic_category_transient_flusher' );
