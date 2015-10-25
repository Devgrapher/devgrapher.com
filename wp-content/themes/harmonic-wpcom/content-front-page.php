<?php
/**
 * @package harmonic
 */
?>
<?php
	$slidethree_background = esc_attr( get_theme_mod( 'harmonic_front_pageimage' ) );

	$slide_path = get_template_directory_uri();

	if ( empty ( $slidethree_background ) ) :
		$slidethree_background = $slide_path . "/images/bcg_slide-3.jpg";
	endif;

	$slidethree_url = "background-image:url( ' " . esc_url( $slidethree_background ) . " ' )";

	$slidethree_layer = esc_attr( get_theme_mod( 'harmonic_front_pagelayer' ) );

	if ( 1 == $slidethree_layer ) :
		$slidethree_layerstyle = "background: rgba(17, 17, 17, 0.6)";
	endif;

	if ( empty ( $slidethree_layerstyle ) ) :
		$slidethree_layerstyle = "";
	endif;
?>

<section id="slide-3" class="slide" data-menu-offset="-80">
	<div class="bcg"
		 data-center="background-position: 50% 0px;"
		 data-top-bottom="background-position: 50% 0px;"
		 data-anchor-target="#slide-3"
		 style="<?php echo $slidethree_url; ?>">

		<div class="hsContainer"
			 style="<?php echo $slidethree_layerstyle; ?>;">

			<div class="hsContent"
			data-center="opacity: 1"
			data-center-top="opacity: 1"
			data--100-bottom="opacity: 1;"
			data-anchor-target="#slide-3">

				<div id="page-section">

					<?php while ( have_posts() ) : the_post(); ?>

						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<header class="entry-header">
								<h1 class="entry-title"><?php the_title(); ?></h1>
							</header><!-- .entry-header .fullwidth-block -->

							<div class="entry-content">
								<?php the_content(); ?>
							</div><!-- .center-block .entry-content -->
						</article><!-- #post-## -->

					<?php endwhile; // end of the loop;?>
    			</div><!-- #page-section -->
			</div><!-- .hsContent -->
		</div><!-- .hsContainer -->
	</div><!-- .bcg -->
</section><!-- #slide-3 .homeSlide -->