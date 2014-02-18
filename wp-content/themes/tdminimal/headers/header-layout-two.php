<header id="masthead" class="site-header site-header-2" role="banner">
	<div class="container">
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4">
				<div class="site-branding">
					<?php tdminimal_website_logo(); ?>
					<?php if( !tdminimal_is_only_logo() ): ?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
					<?php endif; ?>
				</div><!-- .site-branding -->
			</div><!-- .col -->
		
			<div class="col-lg-8 col-md-8 col-sm-8">
				<nav id="site-navigation" class="main-navigation clearfix" role="navigation">
					<div class="pull-right">
						<div class="header-search-button">
							<a id="header-search-button" href="#"><span class="header-search-button-meta"><?php _e( 'Search...', 'tdminimal' ); ?></span><i class="fa fa-search"></i></a>
						</div><!-- .header-search-button -->
						<div class="header-search-box">
							<?php get_search_form(); ?>
							<a id="header-search-button-hide" href="#"><i class="fa fa-times"></i></a>
						</div><!-- .header-search-box -->
					</div><!-- .pull-right -->
					<div class="pull-right">
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
					</div><!-- .pull-right -->
				</nav><!-- #site-navigation -->
			</div><!-- .col -->
		</div><!-- .row -->
	</div><!-- .container -->
</header><!-- #masthead -->