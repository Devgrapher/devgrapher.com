<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package tdMinimal
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php if ( has_post_thumbnail() ): ?>
		<div class="post-thumb">
			<?php the_post_thumbnail(); ?>
		</div><!-- .post-thumb -->
		<?php endif; ?>
		<h2 class="entry-title">
			<span><?php the_title(); ?></span>
		</h2>
	</header><!-- .entry-header -->
	<div class="hentry-inner">
		<div class="entry-content">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'tdminimal') . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>'
				) );
			?>
		</div><!-- .entry-content -->
		<?php edit_post_link( __( 'Edit', 'tdminimal' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>
	</div><!-- .hentry-inner -->
</article><!-- #post-## -->
