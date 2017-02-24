<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Syntax
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<article id="post-0" class="post not-found">
				<header class="entry-header">
					<h1 class="entry-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'syntax' ); ?></h1>
				</header><!-- .entry-header -->

				<div class="entry-content">
					<p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'syntax' ); ?></p>

					<?php get_search_form(); ?>

				</div><!-- .entry-content -->
			</article><!-- #post-0 .post .not-found -->

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>