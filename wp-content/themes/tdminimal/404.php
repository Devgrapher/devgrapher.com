<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package tdMinimal
 */

get_header(); ?>

<div class="container">
	<div class="row">
	<div id="primary" class="content-area col-lg-12">
		<div id="main" class="site-main" role="main">

			<section class="error-404 not-found">
				<div class="page-content">
					<h4 class="error-404-title">404</h4>
					<h2 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'tdminimal' ); ?></h2>
					<p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'tdminimal' ); ?></p>

					<?php get_search_form(); ?>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</div><!-- #main -->
	</div><!-- #primary -->
	</div><!-- .row -->
</div><!-- .container -->

<?php get_footer(); ?>