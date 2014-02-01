<?php
/**
 * The Sidebar containing the main widget area.
 *
 */

$options = runo_get_theme_options();
$current_layout = $options['theme_layout'];

if ( 'content' != $current_layout ) :
?>
	
	<aside id="sidebar" role="complementary">

			<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>
				
					<div class="sidebarbox">
					<h3 class="widget-title"><?php _e( 'Archives', 'runo' ); ?></h3>
					<ul>
						<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
					</ul>
					</div>
					
					<div class="sidebarbox">
					<h3 class="widget-title"><?php _e( 'Meta', 'runo' ); ?></h3>
					<ul>
						<?php wp_register(); ?>
						<li><?php wp_loginout(); ?></li>
						<?php wp_meta(); ?>
					</ul>

					</div>

			<?php endif; // end sidebar widget area ?>
			
	</aside>
		
<?php endif; ?>