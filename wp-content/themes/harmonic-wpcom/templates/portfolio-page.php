<?php
/**
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package harmonic
 *
 * Template Name: Portfolio
 */

get_header( 'portfolio' ); ?>

<div id="skrollr-body">
<?php while ( have_posts() ) : the_post();

	$featuredimage = wp_get_attachment_url( get_post_thumbnail_id() );

	if ( ! empty( $featuredimage ) ) : ?>

		<div id="preload">
			 <img src="<?php echo $featuredimage; ?>">
 		</div>

		<section id="slide-1" class="homeSlide">

	        <div class="bcg"
	        	 data-center="background-position: 50% 0px;"
	        	 data-top-bottom="background-position: 50% -100px;"
	        	 data-anchor-target="#slide-1"
	        	 style="background-image:url('<?php echo esc_url( $featuredimage ); ?>');">

		        <div class="hsContainer">

			    	<div class="hsContent"
			    		 data-center="opacity: 1"
			    		 data-center-top="opacity: 1"
			    		 data--100-bottom="opacity: 0;"
			    		 data-anchor-target="#slide-1">

						<div id="single-titles">
							<div id="single-wrap">
								<h1 class="entry-title">
									<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
								</h1>
								<a href="#content-wrapper" class="button scroll"><?php _e( 'Read more', 'harmonic' ); ?></a>
							</div><!-- #single-wrap -->
						</div><!-- #single-titles -->

			    	</div><!-- .hsContent -->
		        </div><!-- .hsContainer -->
	        </div><!-- .bcg -->
		</section><!-- #slide-1 .homeSlide -->

	<?php endif; ?>
<?php endwhile; // end of the loop. ?>
<div id="content-wrapper" data-menu-offset="-90">
		<div id="content" class="site-wrapper">
			<div id="primary" class="content-area full-width">
				<main id="main" class="site-main" role="main">
					<?php if ( ! get_theme_mod( 'harmonic_hide_portfolio_page_content' ) ) : ?>
						<?php while ( have_posts() ) : the_post(); ?>

							<?php the_title( '<header class="page-header"><h1 class="page-title">', '</h1></header>' ); ?>

							<div class="page-content">
								<?php
									the_content();
									wp_link_pages( array(
										'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'harmonic' ) . '</span>',
										'after'       => '</div>',
										'link_before' => '<span>',
										'link_after'  => '</span>',
									) );
								?>

								<?php edit_post_link( __( 'Edit', 'harmonic' ), '<span class="edit-link">', '</span>' ); ?>
							</div><!-- .page-content -->

						<?php endwhile; // end of the loop. ?>
					<div class="clear"></div>
					<?php endif; ?>
					<div id="archive-container" class="portfolio-projects clear">
						<?php
						if ( get_query_var( 'paged' ) ) :
							$paged = get_query_var( 'paged' );
						elseif ( get_query_var( 'page' ) ) :
							$paged = get_query_var( 'page' );
						else :
							$paged = 1;
						endif;

						$posts_per_page = get_option( 'jetpack_portfolio_posts_per_page', '5' );

						$args = array(
							'post_type'      => 'jetpack-portfolio',
							'paged'          => $paged,
							'posts_per_page' => $posts_per_page,
						);

						$project_query = new WP_Query ( $args );

						if ( $project_query -> have_posts() ) : ?>
								<?php
								while ( $project_query -> have_posts() ) : $project_query -> the_post();
									get_template_part( 'content', 'portfolio' );
								endwhile;
								?>
							<?php
								harmonic_paging_nav( $project_query->max_num_pages );
								wp_reset_postdata();
							?>
						<div class="clear"></div>
						<?php else : ?>

							<section class="no-results not-found">
								<header class="page-header">
									<h1 class="page-title"><?php _e( 'No Project Found', 'harmonic' ); ?></h1>
								</header><!-- .page-header -->

								<div class="page-content">
									<?php if ( current_user_can( 'publish_posts' ) ) : ?>

										<p><?php printf( __( 'Ready to publish your first project? <a href="%1$s">Get started here</a>.', 'harmonic' ), esc_url( admin_url( 'post-new.php?post_type=jetpack-portfolio' ) ) ); ?></p>

									<?php else : ?>

										<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'harmonic' ); ?></p>

									<?php endif; ?>
								</div><!-- .page-content -->
							</section><!-- .no-results -->

						<?php endif; ?>
					</div><!-- #archive-container .portfolio-projects .clear -->
				</main><!-- #main .site-main -->
			</div><!-- #primary .content-area .full-width -->
<?php get_footer('singleportfolio'); ?>