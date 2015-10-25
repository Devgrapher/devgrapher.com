<?php
/**
 * @package harmonic
 */

// Access global variable directly to set content_width
if ( isset( $GLOBALS['content_width'] ) )
	$GLOBALS['content_width'] = 1216;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<footer class="entry-meta">
		<?php
			echo get_the_term_list( $post->ID, 'jetpack-portfolio-tag', '<span class="tags-links">', '', '</span>' );
		?>
		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'harmonic' ), __( '1 Comment', 'harmonic' ), __( '% Comments', 'harmonic' ) ); ?></span>
		<?php endif; ?>
		<?php
		echo get_the_term_list( get_the_ID(), 'jetpack-portfolio-type', '<span class="portfolio-type-links">', _x( ', ', 'Used between list items, there is a space after the comma.', 'harmonic' ), '</span>' );
		?>
		<?php edit_post_link( __( 'Edit', 'harmonic' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->

	<div class="entry-main">

		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header><!-- .entry-header .fullwidth-block -->

		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'harmonic' ) ); ?>
			<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'harmonic' ),
				'after'  => '</div>',
			) );
			?>
		</div><!-- .entry-content -->

	</div><!-- .entry-main -->

</article><!-- #post-## -->
