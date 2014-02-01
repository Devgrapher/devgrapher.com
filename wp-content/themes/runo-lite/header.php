<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="primary">
 *
 */
?><!DOCTYPE html>

<!--[if IE 6]>
<html id="ie6" class="ie-oldie" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" class="ie-oldie" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" class="ie-oldie" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->

<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />


<title><?php

	wp_title( '&para;', true, 'right' );

	
	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_uri(); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->


<?php
	wp_head();
?>

</head>

<body <?php body_class(); ?>>
<div id="container" class="runo-background">
	 <header id="main-header" class="runo-background">
	 
	 	<div id="header-wrapper">
		
		
			<?php $header_image = get_header_image();
			if ( ! empty( $header_image ) ) { ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" />
				</a>
			<?php } // if ( ! empty( $header_image ) ) ?>
		
	
		<hgroup>
	
				<h1 id="site-title"> <span><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span></h1>
				<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
		</hgroup>
				
		<nav id="main-nav" role="navigation">
				
				<h3 class="assistive-text"><?php _e( 'Main menu', 'runo' ); ?></h3>
				<?php /* Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff. */ ?>
				<div class="skip-link"><a class="assistive-text" href="#content" title="<?php esc_attr_e( 'Skip to primary content', 'runo' ); ?>"><?php _e( 'Skip to primary content', 'runo' ); ?></a></div>
				<div class="skip-link"><a class="assistive-text" href="#sidebar" title="<?php esc_attr_e( 'Skip to secondary content', 'runo' ); ?>"><?php _e( 'Skip to secondary content', 'runo' ); ?></a></div>
				<?php /* Our navigation menu. If one isn't filled out, wp_nav_menu falls back to wp_page_menu. The menu assigned to the primary location is the one used. If one isn't assigned, the menu with the lowest ID is used. */ ?>
				<div class="site-navigation menu"><?php wp_nav_menu( array( 'items_wrap' => '<ul>  %3$s </ul>', 'theme_location' => 'primary', 'container' => false) ); ?></div>
				
				
		</nav><!-- #main-nav -->

		</div>

	</header>


	<div id="primary">
	
