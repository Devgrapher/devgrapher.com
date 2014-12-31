<?php
/**
 * The Sidebar containing the header widget area.
 *
 * @package Hexa
 */
?>
<div id="sidebar-toggle-nav" class="panel">
	<div class="widget-areas">
		<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
			<div class="widget-area">
				<?php dynamic_sidebar( 'sidebar-1' ); ?>
			</div>
		<?php endif; ?>
		<?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
			<div class="widget-area">
				<?php dynamic_sidebar( 'sidebar-2' ); ?>
			</div>
		<?php endif; ?>
		<?php if ( is_active_sidebar( 'sidebar-3' ) ) : ?>
			<div class="widget-area">
				<?php dynamic_sidebar( 'sidebar-3' ); ?>
			</div>
		<?php endif; ?>
	</div>
</div>