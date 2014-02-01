<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package tdsimple
 * @since tdsimple 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area twelve columns">
		<div id="content" class="site-content" role="main">

			<article id="post-0" class="post error404 not-found">
				<header class="entry-header">
					<h2 class="entry-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'tdsimple' ); ?></h2>
				</header><!-- .entry-header -->

				<div class="entry-content">
					<p class="sub-info"><?php _e( 'It looks like nothing was found at this location. Maybe try our search?', 'tdsimple' ); ?></p>

					<?php get_search_form(); ?>

				</div><!-- .entry-content -->
			</article><!-- #post-0 .post .error404 .not-found -->

		</div><!-- #content .site-content -->
	</div><!-- #primary .content-area -->

<?php get_footer(); ?>