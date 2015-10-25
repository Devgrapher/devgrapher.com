<?php
/**
 * The Template for displaying all single posts.
 *
 * @package harmonic
 */

get_header('featureimage'); ?>
<main id="single-template">
	<div id="skrollr-body">
		<?php while ( have_posts() ) : the_post();

			$featuredimage = wp_get_attachment_url( get_post_thumbnail_id() );

			if (! empty( $featuredimage )) : ?>

				<div id="preload">
					<img src="<?php echo esc_url( $featuredimage ); ?>">
 				</div><!-- #preload -->

				<section id="slide-feature" class="slide">
	        	<div class="bcg"
	        		data-center="background-position: 50% 0px;"
	        	 	data-top-bottom="background-position: 50% 0px;"
	        	 	data-anchor-target="#slide-feature"
	        	 	style="background-image:url('<?php echo $featuredimage; ?>');">

			        <div class="hsContainer">

				    	<div class="hsContent"
			    		 	data-center="opacity: 1"
			     		 	data-center-top="opacity: 1"
			     		 	data--100-bottom="opacity: 0;"
			    		 	data-anchor-target="#slide-feature">

							<div id="single-titles">
								<div id="single-wrap">
									<h1 class="entry-title">
										<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
									</h1>
									<h2><?php harmonic_posted_on(); ?></h2>
									<a href="#content-wrapper" class="button scroll"><?php _e( 'Read more', 'harmonic' ); ?></a>
								</div><!-- #single-wrap -->
							</div><!-- #single-titles -->
			    		</div><!-- .hsContent -->
		        	</div><!-- .hsContainer -->
	        	</div><!-- .bcg -->
			</section><!-- #slide-feature .slide -->
		<?php endif; ?>

		<section id="slide-content" class="slide">
			<div id="content-wrapper" data-menu-offset="-100">
				<div id="content" class="site-wrapper">
					<div id="primary" class="content-area">
						<div id="main" class="site-main" role="main">
							<?php get_template_part( 'content', 'single' ); ?>
							<div class="clear"></div>
							<?php harmonic_post_nav(); ?>
							<?php
								// If comments are open or we have at least one comment, load up the comment template
								if ( comments_open() || '0' != get_comments_number() ) :
									comments_template();
								endif;
							?>

							<div class="clear"></div>
						</div><!-- #main .site-main -->
					</div><!-- #primary .content-area -->
	<?php endwhile; // end of the loop. ?>

	<?php get_sidebar(); ?>

	<?php get_footer('featureimage'); ?>