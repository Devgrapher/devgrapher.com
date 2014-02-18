<header id="masthead" class="site-header" role="banner">
	<div class="container">
		<div class="site-branding">
			<?php tdminimal_website_logo(); ?>
			<?php if( !tdminimal_is_only_logo() ): ?>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			<?php endif; ?>
		</div>
	
		<nav id="site-navigation" class="main-navigation clearfix" role="navigation">
			<h1 class="menu-toggle"><?php _e( 'Menu', 'tdminimal' ); ?> <i class="fa fa-reorder"></i></h1>
			<?php if ( has_nav_menu( 'primary' ) ) { ?>
				<?php wp_nav_menu( array( 'container' => 'ul', 'menu_class' => 'nav-bar', 'theme_location' => 'primary') ); ?>
			<?php } else { ?>
				<ul class="nav-bar">
					<?php 
						wp_list_pages('title_li=' ); 
					?>
				</ul>
			<?php } ?>
			<div class="header-search-button">
				<a id="header-search-button" href="#"><?php _e( 'Search...', 'tdminimal' ); ?><i class="fa fa-search"></i></a>
			</div><!-- .header-search-button -->
			<div class="header-search-box">
				<?php get_search_form(); ?>
				<a id="header-search-button-hide" href="#"><i class="fa fa-times"></i></a>
			</div>
		</nav><!-- #site-navigation -->
	</div><!-- .container -->
</header><!-- #masthead -->