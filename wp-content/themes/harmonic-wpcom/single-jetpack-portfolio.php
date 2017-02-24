<?php
/**
 * The template for displaying single pages for the portfolio custom post type.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package harmonic
 */

get_header('singleportfolio'); ?>

<div id="skrollr-body">

	<section id="primary" class="content-area full-width">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'portfolio-single' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // end of the loop. ?>

			<?php harmonic_portfolio_pagination(); ?>

		</main><!-- #main .site-main -->
	</section><!-- #primary .content-area .full-width -->

<?php get_footer('singleportfolio'); ?>