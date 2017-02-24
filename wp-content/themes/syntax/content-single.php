<?php
/**
 * @package Syntax
 */

$format = get_post_format();
$formats = get_theme_support( 'post-formats' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php if ( $format && in_array( $format, $formats[0] ) ): ?>
			<a class="entry-format" href="<?php echo esc_url( get_post_format_link( $format ) ); ?>" title="<?php echo esc_attr( sprintf( __( 'All %s posts', 'syntax' ), get_post_format_string( $format ) ) ); ?>"><?php echo get_post_format_string( $format ); ?></a>
		<?php endif; ?>
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'syntax' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
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
		<?php edit_post_link( __( 'Edit', 'syntax' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-## -->
