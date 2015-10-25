<?php
/**
 * @package harmonic
 */
?>
<?php
	$slidefive_background = esc_attr( get_theme_mod( 'harmonic_front_portfolioimage' ) );

	$slide_path = get_template_directory_uri();

	if ( empty ( $slidefive_background ) ) :
		$slidefive_background = $slide_path . "/images/bcg_slide-5.jpg";
	endif;

	$slidefive_url = "background-image:url( ' " . esc_url( $slidefive_background ) . " ' )";

	$slidefive_layer = esc_attr( get_theme_mod( 'harmonic_front_portfoliolayer' ) );

	if ( 1 == $slidefive_layer ) :
		$slidefive_layerstyle = "background: rgba(17, 17, 17, 0.6)";
	endif;

	if ( empty ( $slidefive_layerstyle ) ) :
		$slidefive_layerstyle = "";
	endif;
?>

<section id="slide-5" class="slide" data-menu-offset="-80">
	<div class="bcg"
		 data-center="background-position: 50% 0px;"
		 data-top-bottom="background-position: 50% 0px;"
		 data-anchor-target="#slide-5"
		 style="<?php echo $slidefive_url; ?>">

		<div class="hsContainer"
			 style="<?php echo $slidefive_layerstyle; ?>;">

			<div class="hsContent"
			data-center="opacity: 1"
			data-center-top="opacity: 1"
			data--100-bottom="opacity: 1;"
			data-anchor-target="#slide-5">

				<div id="archive-container" class="portfolio-projects clear">
					<?php
					if ( get_query_var( 'paged' ) ) :
						$paged = get_query_var( 'paged' );
					elseif ( get_query_var( 'page' ) ) :
						$paged = get_query_var( 'page' );
					else :
						$paged = 1;
					endif;

					$posts_per_page = get_option( 'jetpack_portfolio_posts_per_page', '8' );

					$args = array(
						'post_type'      => 'jetpack-portfolio',
						'paged'          => $paged,
						'posts_per_page' => $posts_per_page,
					);

					$project_query = new WP_Query ( $args );

					if ( $project_query -> have_posts() ) : ?>
						<?php
						while ( $project_query -> have_posts() ) : $project_query -> the_post();
							get_template_part( 'content', 'portfoliofront' );
						endwhile;
						?>
						<?php
						wp_reset_postdata();
						?>

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
						</section><!-- .no-results .not-found -->

					<?php endif; ?>
				</div><!-- #archive-container .portfolio-projects .clear -->
			</div><!-- .hsContent -->
		</div><!-- .hsContainer -->
	</div><!-- .bcg -->
</section><!-- #slide-5 .homeSlide -->