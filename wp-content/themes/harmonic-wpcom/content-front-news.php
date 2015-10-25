<?php
/**
 * @package harmonic
 */
?>
<?php
	$slidetwo_background = esc_attr( get_theme_mod( 'harmonic_front_newsimage' ) );

	$slide_path = get_template_directory_uri();

	if ( empty ( $slidetwo_background ) ) :
		$slidetwo_background = $slide_path . "/images/bcg_slide-2.jpg";
	endif;

	$slidetwo_url = "background-image:url( ' " . esc_url( $slidetwo_background ) . " ' )";

	$slidetwo_layer = esc_attr( get_theme_mod( 'harmonic_front_newslayer' ) );

	if ( 1 == $slidetwo_layer ) :
		$slidetwo_layerstyle = "background: rgba(17, 17, 17, 0.6)";
	endif;

	if ( empty ( $slidetwo_layerstyle ) ) :
		$slidetwo_layerstyle = "";
	endif;
?>

<section id="slide-2" class="slide" data-menu-offset="-80">
	<div class="bcg"
		 data-center="background-position: 50% 0px;"
		 data-top-bottom="background-position: 50% 0px;"
		 data-anchor-target="#slide-2"
		 style="<?php echo $slidetwo_url; ?>">

		<div class="hsContainer"
			 style="<?php echo $slidetwo_layerstyle; ?>;">

			<div class="hsContent"
			data-center="opacity: 1"
			data-center-top="opacity: 0"
			data--100-bottom="opacity: 0;"
			data-anchor-target="#slide-2">

				<div id="news-section">
					<?php
					$sticky = get_option( 'sticky_posts' );

					$args = array(
						'posts_per_page'  		=> 7,
						'ignore_sticky_posts' 	=> 1,
						'post__not_in'        	=> $sticky,
					);

					$query = new WP_Query( $args );

					if ( $query->have_posts() ) :

						while ( $query->have_posts() ) : $query->the_post();
							get_template_part( 'content', 'news' );
						endwhile;

					endif; ?>
				</div><!-- #news-section -->
			</div><!-- .hsContent -->
		</div><!-- .hsContainer -->
	</div><!-- .bcg -->
</section><!-- #slide-2 .homeSlide -->