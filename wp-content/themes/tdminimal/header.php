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

<meta name="msvalidate.01" content="81590B6B171A181146DF5658194A0EAF" />

<?php tdminimal_custom_favicon(); ?>
<?php wp_head(); ?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-47679289-1', 'devgrapher.com');
  ga('send', 'pageview');
</script>

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
