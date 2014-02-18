<?php
/**
 * The template used for displaying video content
 *
 * @package tdminimal
 * @since tdminimal 1.0
 */
?>

<?php if( tdminimal_is_two_columns_layout() ): ?>
<div class="hentry-container col-lg-6 col-md-6 post-box">
<?php elseif( tdminimal_is_three_columns_layout() ): ?>
<div class="hentry-container col-lg-4 col-md-4 col-sm-4 post-box">
<?php else: ?>
<div class="hentry-container col-lg-12 col-md-12 post-box">
<?php endif; ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
		<header class="entry-header">
			<?php if ( get_post_meta(get_the_ID(), 'td-video', true) ) : ?>
			<div class="featured-video">
				<?php echo get_post_meta(get_the_ID(), 'td-video', true) ?>
			</div>
			<?php endif; ?>
		
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

			<?php if ( 'post' == get_post_type() ) : ?>
			<div class="entry-meta">
				<?php tdminimal_posted_on(); ?>
				<?php edit_post_link( __( 'Edit', 'tdminimal' ), ' / <span class="edit-link">', '</span>' ); ?>
			</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->
	
		<div class="hentry-inner">
			<?php if ( is_search() ) :?>
			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->
			<?php else : ?>
			<div class="entry-content">
				<?php if( tdminimal_is_auto_summary() ): ?>
					<?php the_excerpt(); ?>
				<?php else: ?>
					<?php the_content( __( 'Continue Reading', 'tdminimal' ) ); ?>
				<?php endif; ?>
				<?php
					wp_link_pages( array(
						'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'tdminimal') . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>'
					) );
				?>
			</div><!-- .entry-content -->
			<?php endif; ?>

			<footer class="entry-meta bottom">
				<span><?php _e( 'Posted by', 'tdminimal' ); ?></span>
				<?php echo tdminimal_get_post_author(); ?>
				<span><?php _e( 'in', 'tdminimal' ); ?> </span>
				<?php echo tdminimal_get_categories(); ?>
			</footer><!-- .entry-meta -->
		</div><!-- .hentry-inner -->
	</article><!-- #post-## -->
</div><!-- .hentry-container -->

