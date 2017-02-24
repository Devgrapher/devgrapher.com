<?php
/**
 * The template for displaying the Static Front Page.
 *
 * @package harmonic
 *
 * Template Name: Front Page
 *
 */

get_header( 'para' ); ?>

<?php //Load our options into variables
	$front_intro     = get_theme_mod( 'harmonic_front_title' );
	$front_news      = get_theme_mod( 'harmonic_front_news' );
	$front_page      = get_theme_mod( 'harmonic_front_page' );
	$front_widgets   = get_theme_mod( 'harmonic_front_widgets' );
	$front_portfolio = get_theme_mod( 'harmonic_front_portfolio' );
?>

<?php
	//If the user has hidden *everything*, display a note linking back to the Customizer
	if ( 1 == $front_intro &&
		1 == $front_page &&
		1 == $front_news &&
		1 == $front_widgets && is_active_sidebar( 'sidebar-2' ) &&
		1 == $front_portfolio &&
		current_user_can( 'publish_posts' ) ) {
			echo '<span class="hidden-content-warning">' . sprintf( __( 'Hey! Your content is hidden! Go to <a href="%1$s">Customize &rarr; Theme Options &rarr; Visibility</a> to fix that.', 'harmonic' ), esc_url( admin_url( 'customize.php' ) ) ) . '</span>';
		}
?>

<div id="preload">
	<?php

	if ( 1 != $front_intro ) :
		$slideone_background = esc_attr( get_theme_mod( 'harmonic_front_titleimage' ) ); ?>

		<img src="<?php echo $slideone_background; ?>">

		<?php if ( empty ( $slideone_background) ) : ?>
		      <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/bcg_slide-1.jpg">
		<?php endif;
	endif;

	if ( 1 != $front_news ) :
		$slidetwo_background = esc_attr( get_theme_mod( 'harmonic_front_newsimage' ) ); ?>

		<img src="<?php echo $slidetwo_background; ?>">

		<?php if ( empty ( $slidetwo_background) ) : ?>
		    <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/bcg_slide-2.jpg">
		<?php endif;
	endif;

	if ( 1 != $front_page ) :
		$slidethree_background = esc_attr( get_theme_mod( 'harmonic_front_pageimage' ) ); ?>

		<img src="<?php echo $slidethree_background; ?>">

		<?php if ( empty ( $slidethree_background) ) : ?>
		    <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/bcg_slide-3.jpg">
		<?php endif;
	endif;

	if ( 1 != $front_widgets && is_active_sidebar( 'sidebar-2' ) ) :
		$slidefour_background = esc_attr( get_theme_mod( 'harmonic_front_widgetimage' ) ); ?>

		<img src="<?php echo $slidefour_background; ?>">

		<?php if ( empty ( $slidefour_background) ) : ?>
			<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/bcg_slide-4.jpg">
		<?php endif;
	endif;

	if ( 1 != $front_portfolio ) :
		$slidefive_background = esc_attr( get_theme_mod( 'harmonic_front_portfolioimage' ) ); ?>

		<img src="<?php echo $slidefive_background; ?>">

		<?php if ( empty ( $slidefive_background) ) : ?>
		    <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/bcg_slide-5.jpg">
		<?php endif;
	endif; ?>

</div><!-- #preload -->
<main id="para-template">
	<div id="skrollr-body">
	<?php
	if ( 1 != $front_intro ) :
		get_template_part( 'content', 'front-intro' );
	endif;

	if ( 1 != $front_news ) :
		get_template_part( 'content', 'front-news' );
	endif;

	if ( 1 != $front_page ) :
		get_template_part( 'content', 'front-page' );
	endif;

	if ( 1 != $front_widgets && is_active_sidebar( 'sidebar-2' ) ) :
		get_template_part( 'content', 'front-widgets' );
	endif;

	if ( 1 != $front_portfolio ) :
		get_template_part( 'content', 'front-portfolio' );
	endif;?>
	</div>

</main><!-- #para-template -->
<?php get_footer( 'para' ); ?>
