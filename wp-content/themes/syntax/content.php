<?php
/**
 * @package Syntax
 */

$format = get_post_format();
$formats = get_theme_support( 'post-formats' );
$hasthumb = 'no-thumbnail';
?>
<?php if ( '' != get_the_post_thumbnail() ) : ?>
	<?php $hasthumb = 'has-thumbnail'; ?>
	<figure class="entry-thumbnail">
		<?php the_post_thumbnail(); ?>
	</figure>
<?php endif; ?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $hasthumb ); ?>>
	<header class="entry-header">
		<?php if ( $format && in_array( $format, $formats[0] ) ): ?>
			<a class="entry-format" href="<?php echo esc_url( get_post_format_link( $format ) ); ?>" title="<?php echo esc_attr( sprintf( __( 'All %s posts', 'syntax' ), get_post_format_string( $format ) ) ); ?>"><?php echo get_post_format_string( $format ); ?></a>
		<?php endif; ?>
		<?php the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' ); ?>
	</header><!-- .entry-header -->

	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'syntax' ) ); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'syntax' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<footer class="entry-meta">
		<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			<?php syntax_posted_on(); ?>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'syntax' ) );
				if ( $tags_list ) :
			?>
			<span class="tags-links">
				<?php echo $tags_list; ?>
			</span>
			<?php endif; // End if $tags_list ?>
		<?php endif; // End if 'post' == get_post_type() ?>

		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
		<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'syntax' ), __( '1 Comment', 'syntax' ), __( '% Comments', 'syntax' ) ); ?></span>
		<?php endif; ?>

		<?php edit_post_link( __( 'Edit', 'syntax' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-## -->
