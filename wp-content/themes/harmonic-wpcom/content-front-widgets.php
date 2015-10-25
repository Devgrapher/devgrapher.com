<?php
/**
 * @package harmonic
 */
?>
<?php
	$slidefour_background = esc_attr( get_theme_mod( 'harmonic_front_widgetimage' ) );

	$slide_path = get_template_directory_uri();

	if ( empty ( $slidefour_background ) ) :
		$slidefour_background = $slide_path . "/images/bcg_slide-4.jpg";
	endif;

	$slidefour_url = "background-image:url( ' " . esc_url( $slidefour_background ) . " ' )";

	$slidefour_layer = esc_attr( get_theme_mod( 'harmonic_front_widgetlayer' ) );

	if ( 1 == $slidefour_layer ) :
		$slidefour_layerstyle = "background: rgba(17, 17, 17, 0.6)";
	endif;

	if ( empty ( $slidefour_layerstyle ) ) :
		$slidefour_layerstyle = "";
	endif;
?>

<section id="slide-4" class="slide" data-menu-offset="-80">

	<div class="bcg"
		 data-center="background-position: 50% 0px;"
		 data-top-bottom="background-position: 50% 0px;"
		 data-anchor-target="#slide-4"
	     style="<?php echo $slidefour_url; ?>">

		<div class="hsContainer" style="<?php echo $slidefour_layerstyle; ?>;">

			<div class="hsContent"
			data-center="opacity: 1"
			data-center-top="opacity: 1"
			data--100-bottom="opacity: 1;"
			data-anchor-target="#slide-4">

				<?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
					<div class="widget-area">
						<?php dynamic_sidebar( 'sidebar-2' ); ?>
					</div><!-- .widget-area -->
				<?php endif; ?>

			</div><!-- .hsContent -->
		</div><!-- .hsContainer -->
	</div><!-- .bcg -->

</section><!-- #slide-4 .homeSlide -->