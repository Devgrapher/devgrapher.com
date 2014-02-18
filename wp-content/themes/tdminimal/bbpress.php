<?php
/**
 * @package tdMinimal
 */

get_header(); ?>

<div class="container">
	<div class="row">	
		<?php if( tdminimal_is_bbpress_sidebar() ): ?>
		<div id="primary" class="content-area col-lg-8 col-md-8">
		<?php else: ?>
		<div id="primary" class="content-area col-lg-12 col-md-12">
		<?php endif; ?>
			<div id="main" class="site-main" role="main">

				<?php while ( have_posts() ) : the_post(); ?>
					<div class="container hentry-container">
					<?php get_template_part( 'content', 'page' ); ?>

					<?php
						// If comments are open or we have at least one comment, load up the comment template
						if ( comments_open() || '0' != get_comments_number() )
							comments_template();
					?>
					</div><!-- .hentry-container -->

				<?php endwhile; // end of the loop. ?>

			</div><!-- #main -->
		</div><!-- #primary -->
		
		<?php if( tdminimal_is_bbpress_sidebar() ): ?>
		<div class="col-lg-4 col-md-4 sidebar-section">
			<?php get_sidebar(); ?>
		</div>
		<?php endif; ?>
		
	</div><!-- .row -->
</div><!-- .container -->

<?php get_footer(); ?>
