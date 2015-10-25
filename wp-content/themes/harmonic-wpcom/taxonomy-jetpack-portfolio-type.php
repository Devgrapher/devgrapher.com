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

			<header class="page-header">
				<h1 class="page-title">
					<?php single_term_title(); ?>
				</h1>
				<?php
					// Show an optional term description.
					$term_description = term_description();
					if ( ! empty( $term_description ) ) :
						printf( '<div class="taxonomy-description">%s</div>', $term_description );
					endif;
				?>
			</header><!-- .page-header -->

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
