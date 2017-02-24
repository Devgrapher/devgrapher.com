<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Syntax
 */
?>
	<div id="secondary" class="widget-area" role="complementary">
		<?php do_action( 'before_sidebar' ); ?>
		<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
			<div class="sidebar-1">
				<?php dynamic_sidebar( 'sidebar-1' ); ?>
			</div>
		<?php endif; ?>
		<?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
			<div class="sidebar-2">
				<?php dynamic_sidebar( 'sidebar-2' ); ?>
			</div>
		<?php endif; ?>
		<?php if ( is_active_sidebar( 'sidebar-3' ) ) : ?>
			<div class="sidebar-3">
				<?php dynamic_sidebar( 'sidebar-3' ); ?>
			</div>
		<?php endif; ?>
	</div><!-- #secondary -->
