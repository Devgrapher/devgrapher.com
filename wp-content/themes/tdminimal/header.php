<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <main id="main">
 *
 * @package tdMinimal
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php echo tdminimal_html_class(); ?>">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php tdminimal_custom_favicon(); ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php
	global $woocommerce;
	if ( class_exists( 'woocommerce' ) ):
?>
	<?php woocommerce_cart_link(); ?>
<?php endif; ?>

<div id="page" class="hfeed site">
	<?php 
		$current_header_layout = tdminimal_get_header_layout();
		
		if( $current_header_layout === 'header-layout-2' ) {
			get_template_part( 'headers/header-layout', 'two' );
		} else if( $current_header_layout === 'header-layout-3' ) {
			get_template_part( 'headers/header-layout', 'three' );
		} else {
			get_template_part( 'headers/header-layout', 'one' );
		}
	?>
	
	<?php tdminimal_get_header_ad(); ?>
	
	<?php tdminimal_breaking_news(); ?>
	
	<div id="content" class="site-content">
