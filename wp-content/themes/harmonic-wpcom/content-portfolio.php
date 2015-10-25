<?php
/**
 * @package harmonic
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="portfolio-item">
		<div class="post-thumbnail-wrapper">
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php harmonic_post_thumbnail(); ?></a>
		</div><!-- .post-thumbnail-wrapper -->

		<div class="project-info">
			<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
		</div><!-- .project-info -->
	</div><!-- .portfolio-item -->

</article><!-- #post-## -->
