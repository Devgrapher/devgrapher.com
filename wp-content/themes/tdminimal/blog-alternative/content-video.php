<?php
/**
 * @package tdMinimal
 */
?>
<?php if( tdminimal_is_two_columns_alt_layout() ): ?>
<div class="hentry-container hentry-alt-container hentry-gallery-container col-lg-6 col-md-6 post-box">
<?php elseif( tdminimal_is_three_columns_alt_layout() ): ?>
<div class="hentry-container hentry-alt-container col-lg-4 col-md-4 col-sm-4 post-box">
<?php else: ?>
<div class="hentry-container hentry-alt-container col-lg-12 col-md-12 post-box">
<?php endif; ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php if ( get_post_meta(get_the_ID(), 'td-video', true) ) : ?>
		<div class="featured-video">
			<?php echo get_post_meta(get_the_ID(), 'td-video', true) ?>
		</div>
		<?php endif; ?>
		<header class="entry-header">
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<?php if ( 'post' == get_post_type() ) : ?>
			<div class="entry-meta">
				<?php tdminimal_posted_on(); ?>
				<span class="sep">&mdash;</span>
				<?php echo tdminimal_get_comment_counts(); ?>
				<?php edit_post_link( __( 'Edit', 'tdminimal' ), ' / <span class="edit-link">', '</span>' ); ?>
			</div><!-- .entry-meta -->
			<?php endif; ?>	
		</header><!-- .entry-header -->

	</article><!-- #post-## -->
</div><!-- .hentry-container -->
