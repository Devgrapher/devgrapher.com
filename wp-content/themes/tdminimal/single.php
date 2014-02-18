<?php
/**
 * The Template for displaying all single posts.
 *
 * @package tdMinimal
 */

get_header(); ?>

<?php $post_template = tdminimal_post_page_template(); ?>

<div class="container">
	<div class="row">	
		<div id="primary" class="content-area <?php echo $post_template['span_class']; ?>">
			<div id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
				<div class="container hentry-container">
					<?php get_template_part( 'content', 'single' ); ?>

					<?php //tdminimal_content_nav( 'nav-below' ); ?>
					
					<?php tdminimal_get_before_comment_ad(); ?>

					<?php
						if ( comments_open() || '0' != get_comments_number() )
							comments_template();
					?>
					
				</div><!-- .hentry-container -->
			<?php endwhile; // end of the loop. ?>

			</div><!-- #main -->
		</div><!-- #primary -->

		<?php if( $post_template['is_sidebar'] && $post_template['right_sidebar'] ): ?>
		<div class="col-lg-4 col-md-4 sidebar-section">
			<?php get_sidebar(); ?>
		</div>
		<?php elseif( $post_template['is_sidebar'] && $post_template['left_sidebar'] ): ?>
		<div class="col-lg-4 col-md-4 left-sidebar sidebar-section">
			<?php get_sidebar(); ?>
		</div>
		<?php endif; ?>
		
	</div><!-- .row -->
</div><!-- .container -->

<?php get_footer(); ?>