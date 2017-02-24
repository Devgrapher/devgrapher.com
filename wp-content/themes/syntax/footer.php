<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Syntax
 */
?>

	</div><!-- #main -->
	<footer id="colophon" class="site-footer" role="contentinfo">
		<?php if ( has_nav_menu( 'secondary' ) ) : ?>
			<div class="secondary-navigation">
				<?php wp_nav_menu( array( 'theme_location' => 'secondary', 'depth' => 1 ) ); ?>
			</div>
		<?php endif; ?>
		<div class="site-info">
			<?php do_action( 'syntax_credits' ); ?>
			<a href="http://wordpress.org/" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'syntax' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'syntax' ), 'WordPress' ); ?></a>
			<span class="sep"> ~ </span>
			<?php printf( __( 'Theme: %1$s by %2$s.', 'syntax' ), 'Syntax', '<a href="https://wordpress.com/themes/" rel="designer">WordPress.com</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>