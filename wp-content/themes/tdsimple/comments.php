<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to tdsimple_comment() which is
 * located in the functions.php file.
 *
 * @package tdsimple
 * @since tdsimple 1.0
 */
?>

<?php
	/*
	 * If the current post is protected by a password and
	 * the visitor has not yet entered the password we will
	 * return early without loading the comments.
	 */
	if ( post_password_required() )
		return;
?>

	<div id="comments" class="comments-area">

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<h3 class="comments-title">
			<?php
				printf( _nx( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'tdsimple' ),
					number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
			?>
		</h3>

		<ol class="commentlist">
			<?php
				/* Loop through and list the comments. Tell wp_list_comments()
				 * to use tdsimple_comment() to format the comments.
				 * If you want to overload this in a child theme then you can
				 * define tdsimple_comment() and that will be used instead.
				 * See tdsimple_comment() in inc/template-tags.php for more.
				 */
				wp_list_comments( array( 'callback' => 'tdsimple_comment' ) );
			?>
		</ol><!-- .commentlist -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav role="navigation" id="comment-nav-below" class="site-navigation comment-navigation">
			<h1 class="assistive-text"><?php _e( 'Comment navigation', 'tdsimple' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'tdsimple' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'tdsimple' ) ); ?></div>
		</nav><!-- #comment-nav-below .site-navigation .comment-navigation -->
		<?php endif; // check for comment navigation ?>

	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'tdsimple' ); ?></p>
	<?php endif; ?>

	<?php 
		
			$comments_settings = array(                                                
				'id_submit' => 'submit-comment',
				'comment_notes_before' => '',
				'fields' => array(
					'author' => '<div class="row"><p class="comment-form-author four columns">' . '<label for="author">' . __( 'Name', 'tdblu' ) . ( $req ? '<span class="required">*</span>' : '' )  .'</label> <input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" /></p>',
					'email' => '<p class="comment-form-email four columns"><label for="email">' . __( 'Email', 'tdblu' ) . ( $req ? '<span class="required">*</span>' : '' ) . '</label> <input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" /></p>',
					'url' => '<p class="comment-form-url four columns"><label for="url">' . __( 'Website', 'tdblu' ) . '</label>' . '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p></div>',
				),
				'comment_notes_after'  => ''
			);
		
			comment_form($comments_settings);
	?>

</div><!-- #comments .comments-area -->
