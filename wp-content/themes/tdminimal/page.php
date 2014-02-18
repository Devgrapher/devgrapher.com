<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package tdMinimal
 */

get_header(); ?>
<?php $page_template = tdminimal_post_page_template(); ?>

<div class="container">
	<div class="row">	
		<div id="primary" class="content-area <?php echo $page_template['span_class']; ?>">
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

		<?php if( $page_template['is_sidebar'] && $page_template['right_sidebar'] ): ?>
		<div class="col-lg-4 col-md-4 sidebar-section">
			<?php get_sidebar(); ?>
		</div>
		<?php elseif( $page_template['is_sidebar'] && $page_template['left_sidebar'] ): ?>
		<div class="col-lg-4 col-md-4 left-sidebar sidebar-section">
			<?php get_sidebar(); ?>
		</div>
		<?php endif; ?>
	</div><!-- .row -->
</div><!-- .container -->

<?php get_footer(); ?>
