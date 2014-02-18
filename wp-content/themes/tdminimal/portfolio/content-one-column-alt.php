<?php 
/**
 * One Column (Alternative) Portfolio Layout
 *
 * @package tdminimal
 */
?>

<div class="hentry-container col-lg-12 col-md-12 portfolio-archive-item<?php echo tdminimal_portfolio_post_filter_classes( get_the_ID() ); ?>">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<?php if ( has_post_thumbnail() ): ?>
			<div class="post-thumb">
				<?php if( tdminimal_is_featured_image_as_thumb() ): ?>
					<?php the_post_thumbnail('front-page-thumb'); ?>
				<?php else: ?>
					<?php the_post_thumbnail(); ?>
				<?php endif; ?>
				<a href="<?php the_permalink(); ?>" class="thumb-mask" alt="<?php the_title(); ?>"></a>
				<a href="<?php the_permalink(); ?>" class="thumb-mask-link-icon" alt="<?php the_title(); ?>"><i class="fa fa-link"></i></a>
			</div><!-- .post-thumb -->
			<?php endif; ?>
	
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

			<?php if ( 'post' == get_post_type() ) : ?>
			<div class="entry-meta">
				<?php tdminimal_posted_on(); ?>
				<?php edit_post_link( __( 'Edit', 'tdminimal' ), ' / <span class="edit-link">', '</span>' ); ?>
			</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->
	</article><!-- #post-## -->
</div><!-- .portfolio-archive-container -->
