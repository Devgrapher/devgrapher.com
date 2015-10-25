<?php
/**
 * @package harmonic
 */
?>

<?php
	$slideone_background = esc_attr( get_theme_mod( 'harmonic_front_titleimage' ) );

	$slide_path = get_template_directory_uri();

	if ( empty ( $slideone_background ) ) :
		$slideone_background = $slide_path . "/images/bcg_slide-1.jpg";
	endif;

	$slideone_url = "background-image:url( ' " . esc_url( $slideone_background ) . " ' )";

	$slideone_layer = get_theme_mod( 'harmonic_front_titlelayer' );

	if ( 1 == $slideone_layer ) :
		$slideone_layerstyle = "background: rgba(17, 17, 17, 0.6);";
	endif;

	if ( empty ( $slideone_layerstyle ) ) :
		$slideone_layerstyle = "";
	endif;
?>

 <section id="slide-1" class="slide">
	<div class="bcg"
		 data-center="background-position: 50% 0px;"
		 data-top-bottom="background-position: 50% 0px;"
		 data-anchor-target="#slide-1"

		 style="<?php echo $slideone_url; ?>">

		<div class="hsContainer"
			 style="<?php echo $slideone_layerstyle; ?>">

			<div class="hsContent"
			data-center="opacity: 1"
			data-center-top="opacity: 0"
			data--100-bottom="opacity: 0;"
			data-anchor-target="#slide-1">

				<div id="header-branding">


					<?php
					$front_description = get_theme_mod( 'harmonic_hide_description' );
					if ( 1 != $front_description ) { ?>
						<?php if ( function_exists( 'jetpack_the_site_logo' ) ) {
							harmonic_the_site_logo(); ?>
							<h2 class="header-description"><?php bloginfo( 'description' ); ?></h2>
						<?php } else { ?>
							<h1 class="header-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
							<h2 class="header-description"><?php bloginfo( 'description' ); ?></h2>
						<?php }
					} else { ?>
						<?php if ( function_exists( 'jetpack_the_site_logo' ) ) {
							harmonic_the_site_logo(); ?>
						<?php } else { ?>
							<h1 class="header-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php }
					} ?>
				</div><!-- #header-branding -->
			</div><!-- .hsContent -->
		</div><!-- .hsContainer -->
	</div><!-- .bcg -->
</section><!-- #slide-1 .homeSlide -->