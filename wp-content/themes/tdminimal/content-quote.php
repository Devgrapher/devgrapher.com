<?php
/**
 * The template used for displaying quote content
 *
 * @package tdminimal
 * @since tdminimal 1.0
 */
?>

<?php if( tdminimal_is_two_columns_layout() ): ?>
<div class="hentry-container hentry-quote-container col-lg-6 col-md-6 post-box">
<?php elseif( tdminimal_is_three_columns_layout() ): ?>
<div class="hentry-container col-lg-4 col-md-4 col-sm-4 post-box">
<?php else: ?>
<div class="hentry-container hentry-quote-container col-lg-12 col-md-12 post-box">
<?php endif; ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
		<header class="entry-header">
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		</header><!-- .entry-header -->
	
		<div class="hentry-inner">
			<?php if ( is_search() ) :?>
			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->
			<?php else : ?>
			<div class="entry-content">
				<a href="<?php the_permalink(); ?>">
				<?php the_content( '' ); ?>
				</a>
			</div><!-- .entry-content -->
			<?php endif; ?>
		</div><!-- .hentry-inner -->
	</article><!-- #post-## -->
</div><!-- .hentry-container -->

