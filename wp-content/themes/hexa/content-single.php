<?php
/**
 * @package Hexa
 */

$format = get_post_format();
$formats = get_theme_support( 'post-formats' );
?>

<?php if ( '' != get_the_post_thumbnail() && 'image' == $format ) : ?>
	<figure class="entry-thumbnail">
		<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_post_thumbnail( 'index-thumb' ); ?></a>
	</figure>
<?php endif; ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php if ( $format && in_array( $format, $formats[0] ) ): ?>
			<a class="entry-format" href="<?php echo esc_url( get_post_format_link( $format ) ); ?>" title="<?php echo esc_attr( sprintf( __( 'All %s posts', 'hexa' ), get_post_format_string( $format ) ) ); ?>"><span class="screen-reader-text"><?php echo get_post_format_string( $format ); ?></span></a>
		<?php else : ?>
			<span class="entry-format"></span>
		<?php endif; ?>
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">', 'after' => '</div>', 'link_before' => '<span class="active-link">', 'link_after' => '</span>' ) ); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php hexa_posted_on(); ?>
		<?php
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', __( ', ', 'hexa' ) );
			if ( $tags_list ) :
		?>
		<span class="tags-links">
			<?php echo $tags_list; ?>
		</span>
		<?php endif; // End if $tags_list ?>
		<?php edit_post_link( __( 'Edit', 'hexa' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-## -->
