<?php
/**
 * The Template for displaying all single posts.
 *
 * @package tdsimple
 * @since tdsimple 1.0
 */

get_header(); ?>

		<div id="primary" class="content-area twelve columns">
			<div id="content" class="site-content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>


				<?php 
					get_template_part( 'content', 'single' );
				?>

				<?php tdsimple_content_nav( 'nav-below' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() )
						comments_template( '', true );
						
					// Check if comments are closed
					if( $post->comment_status == "closed" ) {
						echo '
								<div id="comments" class="comments-area">
									<p class="disabled-comments">'.__('Comments are disabled', 'tdsimple').'</p>
								</div>
							 ';
					}
				?>

			<?php endwhile; // end of the loop. ?>

			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>