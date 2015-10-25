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
	</div><!-- .portfolio-item -->

</article><!-- #post-## -->
