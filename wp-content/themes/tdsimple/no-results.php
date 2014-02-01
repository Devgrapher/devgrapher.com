<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package tdsimple
 * @since tdsimple 1.0
 */
?>

<article id="post-0" class="post no-results not-found">
	<header class="entry-header">
		<h2 class="entry-title"><?php _e( 'Nothing Found', 'tdsimple' ); ?></h2>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p class="sub-info"><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'tdsimple' ), admin_url( 'post-new.php' ) ); ?></p>

		<?php elseif ( is_search() ) : ?>

			<p class="sub-info"><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'tdsimple' ); ?></p>
			<?php get_search_form(); ?>

		<?php else : ?>

			<p class="sub-info"><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'tdsimple' ); ?></p>
			<?php get_search_form(); ?>

		<?php endif; ?>
	</div><!-- .entry-content -->
</article><!-- #post-0 .post .no-results .not-found -->
