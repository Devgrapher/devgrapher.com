<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package tdMinimal
 */

if ( ! function_exists( 'tdminimal_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 */
function tdminimal_content_nav( $nav_id ) {
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

	$nav_class = ( is_single() ) ? 'post-navigation' : 'paging-navigation';

	?>
	<nav role="navigation" id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo $nav_class; ?>">
	<?php if ( is_single() ) : // navigation links for single posts ?>
		
		<?php if( tdminimal_is_portfolio_cat_exclude() ): ?>
		
			<?php if( tdminimal_is_in_portfolio_category( get_the_ID() ) ): ?>
				<div class="post-navigation-sep"></div>
				<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-title">'.__( 'Previous Article', 'tdminimal' ).'</span><span class="meta-nav">' . _x( '', 'Previous post link', 'tdminimal' ) . '</span> %title', true, tdminimal_get_portfolio_category_id() ); ?>
				<?php next_post_link( '<div class="nav-next">%link</div>', '<span class="meta-title">'.__( 'Next Article', 'tdminimal' ).'</span> %title <span class="meta-nav">' . _x( '', 'Next post link', 'tdminimal' ) . '</span>', true, tdminimal_get_portfolio_category_id() ); ?>
			<?php else: ?>
				<div class="post-navigation-sep"></div>
				<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-title">'.__( 'Previous Article', 'tdminimal' ).'</span><span class="meta-nav">' . _x( '', 'Previous post link', 'tdminimal' ) . '</span> %title', false, tdminimal_get_portfolio_category_id() ); ?>
				<?php next_post_link( '<div class="nav-next">%link</div>', '<span class="meta-title">'.__( 'Next Article', 'tdminimal' ).'</span> %title <span class="meta-nav">' . _x( '', 'Next post link', 'tdminimal' ) . '</span>', false, tdminimal_get_portfolio_category_id() ); ?>
			<?php endif; ?>
		
		<?php else: ?>
			<div class="post-navigation-sep"></div>
			<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-title">'.__( 'Previous Article', 'tdminimal' ).'</span><span class="meta-nav">' . _x( '', 'Previous post link', 'tdminimal' ) . '</span> %title' ); ?>
			<?php next_post_link( '<div class="nav-next">%link</div>', '<span class="meta-title">'.__( 'Next Article', 'tdminimal' ).'</span> %title <span class="meta-nav">' . _x( '', 'Next post link', 'tdminimal' ) . '</span>' ); ?>
		<?php endif; ?>
		
	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>
		
		<?php if( !tdminimal_is_numeric_pagination() ): ?>
			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav"><i class="fa fa-angle-left"></i></span> <span class="meta-nav-direction">Older posts</span>', 'tdminimal' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( '<span class="meta-nav-direction">Newer posts</span> <span class="meta-nav"><i class="fa fa-angle-right"></i></i></span>', 'tdminimal' ) ); ?></div>
			<?php endif; ?>
		<?php else: ?>
			<?php tdminimal_numeric_pagination(); ?>
		<?php endif; ?>
		
	<?php endif; ?>

	</nav><!-- #<?php echo esc_html( $nav_id ); ?> -->
	<?php
}
endif; // tdminimal_content_nav

if ( ! function_exists( 'tdminimal_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function tdminimal_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;

	if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<div class="comment-body">
			<?php _e( 'Pingback:', 'tdminimal' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'tdminimal' ), '<span class="edit-link">', '</span>' ); ?>
		</div>

	<?php else : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			
			<div class="comment-author vcard clearfix">
				<div class="avatar-container pull-left">
					<?php echo get_avatar( $comment, 128 ); ?> 
				</div>
				<div class="comment-meta pull-left">
					<div class="comment-author-name"><?php printf( __( '%s', 'tdminimal' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?></div>
					<a class="commentmetadata" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php
							setlocale(LC_TIME, get_locale());
							/* translators: 1: date, 2: time */
							printf( __( '%1$s at %2$s', 'tdminimal' ), strftime('%B %e, %Y', get_comment_date('U')), get_comment_time() ); 
							?>
						</time>
					</a>
					<span class="reply"> 
						<span class="sep"><i class="icon-comments"></i></span>
						<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
					</span><!-- .reply -->
				</div>
			</div>		
	
			<div class="comment-content"><?php comment_text(); ?></div>		
		</article><!-- #comment-## -->
	<?php
	endif;
}
endif; // ends check for tdminimal_comment()

if ( ! function_exists( 'tdminimal_the_attached_image' ) ) :
/**
 * Prints the attached image with a link to the next attached image.
 */
function tdminimal_the_attached_image() {
	$post                = get_post();
	$attachment_size     = apply_filters( 'tdminimal_attachment_size', array( 1200, 1200 ) );
	$next_attachment_url = wp_get_attachment_url();

	/**
	 * Grab the IDs of all the image attachments in a gallery so we can get the
	 * URL of the next adjacent image in a gallery, or the first image (if
	 * we're looking at the last image in a gallery), or, in a gallery of one,
	 * just the link to that image file.
	 */
	$attachment_ids = get_posts( array(
		'post_parent'    => $post->post_parent,
		'fields'         => 'ids',
		'numberposts'    => -1,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order ID'
	) );

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id == $post->ID ) {
				$next_id = current( $attachment_ids );
				break;
			}
		}

		// get the URL of the next image attachment...
		if ( $next_id )
			$next_attachment_url = get_attachment_link( $next_id );

		// or get the URL of the first image attachment.
		else
			$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
	}

	printf( '<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>',
		esc_url( $next_attachment_url ),
		the_title_attribute( array( 'echo' => false ) ),
		wp_get_attachment_image( $post->ID, $attachment_size )
	);
}
endif;

if ( ! function_exists( 'tdminimal_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function tdminimal_posted_on() {
	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	
	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	printf( __( '<span class="posted-on"><i class="fa fa-calendar"></i> %1$s</span>', 'tdminimal' ),
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			$time_string
		)
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category
 */
function tdminimal_categorized_blog() {
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
		// This blog has more than 1 category so tdminimal_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so tdminimal_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in tdminimal_categorized_blog
 */
function tdminimal_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'tdminimal_category_transient_flusher' );
add_action( 'save_post',     'tdminimal_category_transient_flusher' );

/**
 * Return Post Author
 *
 * @since tdminimal 1.0
 */
function tdminimal_get_post_author() {
	return sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'tdminimal' ), get_the_author() ) ),
			esc_html( get_the_author() )
	);
}

/**
 * Post Tags
 *
 * @since tdminimal 1.0
 */
function tdminimal_get_tags() {
	$tag_list = get_the_tag_list( '', __( ' ', 'tdminimal' ) );
	if ( $tag_list ) {
		echo '<span class="tags-title">'.__( 'Tags:', 'tdminimal' ).'</span>' . $tag_list;
	}
}

/**
 * Post Categories 
 *
 * @since tdminimal 1.0
 */
function tdminimal_get_categories() {
	$category_list = get_the_category_list( __( '<span class="comma">,</span> ', 'tdminimal' ) );
	if ( $category_list ) {
		return '<span class="entry-cats">'. $category_list . '</span>';
	}
 }
 
 /**
 * Post Comments
 *
 * @since tdminimal 1.0
 */
 function tdminimal_get_comment_counts() {
 	ob_start();
	comments_popup_link( __( 'Leave a Comment', 'tdminimal' ), __( '1 Comment', 'tdminimal' ), __( '% Comments', 'tdminimal' ) );
	$count_comments = ob_get_contents();
	ob_end_clean();
	
	return '<span class="entry-comments">' . $count_comments . '</span>';
 }