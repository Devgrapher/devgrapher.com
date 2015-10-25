<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package harmonic
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div id="page" class="hfeed site">
	<?php do_action( 'before' ); ?>

	<header id="masthead" role="banner">
		<div id="mobile-panel">
			<div id="mobile-link">
				<span id="menu-title"><?php _e( 'Menu', 'harmonic' ); ?></span>
			</div><!-- #mobile-link -->
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
		</div><!-- #mobile-panel -->
		<div id="mobile-block">
			<nav id="site-navigation" class="main-navigation" role="navigation">
				<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'harmonic' ); ?></a>
				<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
			</nav><!-- #site-navigation .main-navigation -->
		</div><!-- #menu-block-->

		<div id="site-branding">
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php
			if ( has_nav_menu( 'social' ) ) : ?>
				<div id="social-links-wrapper">
					<?php wp_nav_menu( array(
						'theme_location'  => 'social',
						'container_class' => 'social-links',
						'menu_class'      => 'clear',
						'link_before'     => '<span class="screen-reader-text">',
						'link_after'      => '</span>',
					) ); ?>
				</div>
			<?php endif; ?>
		</div><!-- #site-branding -->

		<nav id="site-navigation" class="desktop-nav main-navigation site-wrapper" role="navigation">
			<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'harmonic' ); ?></a>
			<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
		</nav><!-- #site-navigation .desktop-nav .main-navigation .site-wrapper -->

	</header><!-- #masthead -->