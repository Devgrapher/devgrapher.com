<?php
/**
 * @package tdMinimal
 */
?>

<?php if( tdminimal_is_two_columns_layout() || tdminimal_is_two_columns_alt_layout() ): ?>
<div class="hentry-container hentry-alt-container col-lg-6 col-md-6 post-box">
<?php elseif( tdminimal_is_three_columns_alt_layout() ): ?>
<div class="hentry-container hentry-alt-container col-lg-4 col-md-4 col-sm-4 post-box">
<?php else: ?>
<div class="hentry-container hentry-alt-container col-lg-12 col-md-12 post-box">
<?php endif; ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
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
