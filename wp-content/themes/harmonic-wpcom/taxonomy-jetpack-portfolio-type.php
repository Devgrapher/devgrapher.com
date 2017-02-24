<?php
/**
 * The template for displaying Archive pages for Portfolio items.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package harmonic
 */

get_header(); ?>
<div id="skrollr-body">
<section id="primary" class="content-area full-width">
	<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<?php harmonic_portfolio_thumbnail( '<div class="portfolio-featured-image">', '</div>' ); ?>

			<header class="page-header">
				<?php harmonic_portfolio_title( '<h1 class="page-title">', '</h1>' ); ?>

				<?php harmonic_portfolio_content( '<div class="taxonomy-description">', '</div>' ); ?>
			</header>

			<?php /* Start the Loop */ ?>
			<div id="archive-container" class="portfolio-projects clear">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'portfolio' ); ?>

				<?php endwhile; ?>

				<?php harmonic_paging_nav(); ?>

			</div><!-- #archive-container .portfolio-projects .clear -->

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>


	</main><!-- #main .site-main -->
</section><!-- #primary .content-area .full-width -->

<?php get_footer('singleportfolio'); ?>
